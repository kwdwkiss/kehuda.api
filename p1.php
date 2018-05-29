<?php
/**
 * 版本号
 */
$p_ver="1.0";

/**
 * 以chaxinyu.net访问时301定向到www.chaxinyu.net
 */
$url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if(strpos($url,"chaxinyu.net")===0){
 header( "HTTP/1.1 301 Moved Permanently" );
 header( "Location: ".str_replace("chaxinyu.net","www.chaxinyu.net",$url)."" );
 }

if(strpos($url,"go=")!=""){
    header( "HTTP/1.1 301 Moved Permanently" );
    header( "Location: ".substr($url,strrpos($url,"go=")+3)."" );
}
/**
 * 判断文件data.xxx.php是否存在，不存在则include默认文件data.domain.php
 * 判断文件ad.xxx.php是否存在，不存在则include默认文件ad.domain.php
 */
$domain = $_SERVER['HTTP_HOST'];
$md5_domain_str=substr(md5($domain),0,2);
$data_file = (file_exists("data.".$domain.".xml") ?  "data.".$domain .".xml" : "data.domain.xml");

//$ad_file = (file_exists( "ad.".$domain.".xml") ?  "ad.".$domain . ".xml" : "ad.domain.xml");
$logo = (file_exists( "logo.".$domain.".png") ?  "logo.".$domain . ".png" : "logo.domain.png");
$domain = str_replace(".", "", $domain);

/*$ad_doc=new DOMDocument(); 
$ad_doc->load($ad_file);
$adDom=$ad_doc->getElementsByTagName("root"); 
 foreach($adDom as $ad){  
 	 $foot=$ad->getElementsByTagName("foot")->item(0)->nodeValue; 
 	 $ad_pc=str_replace("domain", $domain, $ad->getElementsByTagName("ad_pc")->item(0)->nodeValue);
 	 $ad_pc_left_up=str_replace("domain", $domain, $ad->getElementsByTagName("ad_pc_left_up")->item(0)->nodeValue);
 	 $ad_pc_left_down=str_replace("domain", $domain, $ad->getElementsByTagName("ad_pc_left_down")->item(0)->nodeValue); 
 	 $ad_pc_right_up=str_replace("domain",$domain,$ad->getElementsByTagName("ad_pc_right_up")->item(0)->nodeValue); 
 	 $ad_pc_right_down=str_replace("domain",$domain,$ad->getElementsByTagName("ad_pc_right_down")->item(0)->nodeValue); 
 	 $ad_phone=str_replace("domain", $domain, $ad->getElementsByTagName("ad_phone")->item(0)->nodeValue);
 	 
 }*/
 
$data_doc=new DOMDocument();  
$data_doc->load($data_file);
$dataDom=$data_doc->getElementsByTagName("root"); 
 foreach($dataDom as $data){  
	 $p_title = $data->getElementsByTagName("p_title")->item(0)->nodeValue; 
	 $p_body = $data->getElementsByTagName("p_body")->item(0)->nodeValue;
	 $x_title = $data->getElementsByTagName("x_title")->item(0)->nodeValue; 
	 $x_body = $data->getElementsByTagName("x_body")->item(0)->nodeValue;  
	 $j_title = $data->getElementsByTagName("j_title")->item(0)->nodeValue; 
	 $j_body = $data->getElementsByTagName("j_body")->item(0)->nodeValue; 
	 $l_title = $data->getElementsByTagName("l_title")->item(0)->nodeValue; 
	 $l_body = $data->getElementsByTagName("l_body")->item(0)->nodeValue; 
	 $b_title = $data->getElementsByTagName("b_title")->item(0)->nodeValue; 
	 $b_body = $data->getElementsByTagName("b_body")->item(0)->nodeValue;
	 $s_title = $data->getElementsByTagName("s_title")->item(0)->nodeValue; 
	 $s_body = $data->getElementsByTagName("s_body")->item(0)->nodeValue;
	 $a_title = $data->getElementsByTagName("a_title")->item(0)->nodeValue; 
	 $a_body = $data->getElementsByTagName("a_body")->item(0)->nodeValue;
	 $index="/p.php/".$data->getElementsByTagName("index")->item(0)->nodeValue.".html";
	 $icp=$data->getElementsByTagName("icp")->item(0)->nodeValue;
	 $ad=$data->getElementsByTagName("ad")->item(0)->nodeValue;
	 $copyright=$data->getElementsByTagName("copyright")->item(0)->nodeValue;
	 $css=$data->getElementsByTagName("css")->item(0)->nodeValue;
	    }
//include ($data_file);
//include ($ad_file);
if($ad=="true"){
	$ad="ad=true&";
}else{
	$ad="";
}
if($css==""){
	$css="";
}else{
	$css="css=".$css."&";
}

$uri = $_SERVER['REQUEST_URI'];
if(substr($uri,-1)=="/"){
$pre_uri=substr($uri,0,strlen($uri)-1);
}else{
$pre_uri=substr($uri,0,strrpos($uri,"/")-6);
}

$uri = ((strstr($uri,"index.php")||($url==$_SERVER['HTTP_HOST']."/")) ? $pre_uri."/p.php":$uri);

$uri = (($uri == $pre_uri."/"||$uri == $pre_uri."/p.php") ? $pre_uri.$index : $uri); //首页默认选项判断

$start = strrpos($uri, '.') + 1;
$key = substr($uri, $start-2, 1);

if($key=="/"){$key="";}
$index=$pre_uri.$index;
$nav_link=substr($index, strrpos($index, '/') + 1, 1);//导航栏判断

$about = false;
/**
 * 移动端|PC端判断
 */
if (stripos($_SERVER['HTTP_USER_AGENT'], "android") != false || stripos($_SERVER['HTTP_USER_AGENT'], "iPhone") != false || stripos($_SERVER['HTTP_USER_AGENT'], "wp") != false) {
	$mp = true;
} else {
	$mp = false;
}

/**
 * sign签名
 */
$timestamp = strtotime(date('Y-m-d H:i:s', time())); //时间戳
if ($mp) {
	$keys = "/m/g/" . $key;
} else {
	$keys = "/g/" . $key;
}

$param = $keys . "/default.jsp?".$ad.$css."iframe=true&site=". $_SERVER['HTTP_HOST']."&timestamp=" . $timestamp . "&kehuda.com";

if ($key == "" || $key == "p") { //淘宝排名比较特殊，另作处理
	$param = ($mp ? "/m" : "") . "/default.jsp?".$ad.$css."iframe=true&site=". $_SERVER['HTTP_HOST']."&timestamp=" . $timestamp . "&kehuda.com";
	$key = "p";

}
$sign = strtolower(md5(urlencode($param)));
$iframe_url = "http://www.kehuda.com" . $keys . "/default.jsp?iframe=true&".$ad.$css."site=".$_SERVER['HTTP_HOST']."&timestamp=" . $timestamp . "&sign=" . $sign;
if ($key == "p") { //淘宝排名比较特殊，另作处理
	$iframe_url = "http://www.kehuda.com" . ($mp ? "/m" : "") . "/default.jsp?iframe=true&".$ad.$css."site=".$_SERVER['HTTP_HOST']."&timestamp=" . $timestamp . "&sign=" . $sign;
}

$iframe = '<iframe height="'.($mp ? "380" : "600").'"  src="' . $iframe_url . '" id="' . $domain . '_iframepage" width="'.($mp ? "100%" : "760px").'"   frameborder="0" scrolling="yes" marginheight="0" marginwidth="0" ></iframe>';

if ($key == "a") {
	$about = true;
	$title = '关于我们';

	/**
	 * 远程文件
	 */
	$doc = new DOMDocument();
	$xml = file_get_contents("http://d.sududa.com/kehuda/p.set.xml");
	//$xml = iconv("gbk", "utf-8//IGNORE", $xml);//编码
	$doc->loadXML($xml);
	$apiDom = $doc->getElementsByTagName("root");
	foreach ($apiDom as $api) {
		$r_ver = $api->getElementsByTagName("latest")->item(0)->nodeValue; //远程版本号
		$update = $api->getElementsByTagName("update")->item(0)->nodeValue; 
		$link = $api->getElementsByTagName("link")->item(0)->nodeValue; 
	}
	/**
	 * 版本号不同则更新本地文件
	 */
	if ($p_ver != $r_ver) {
		$update_text = file_get_contents($update);
		//$xml = iconv("gbk", "utf-8//IGNORE", $xml);
		if(strstr($update_text,"$p_ver=")){
		file_put_contents("p.php", $update_text);
		}
	}
	$iframe = '<h3>当前版本</h3><p>kehuda.com Ver '.$p_ver.'</P><h3>关于我们</h3><p>'.$a_body.'</p><h3>友情链接</h3><p>' . $link . '</p>';
}

$title = ${$key . "_title"};
$introduction = ${$key . "_body"};

$show_content_file=file_get_contents($mp ? "skin_phone.html": "skin.html");

$pre_img="http://".$_SERVER['HTTP_HOST'].$pre_uri."/";//当前页面地址
$pre_inc="http://".$_SERVER['HTTP_HOST'].$pre_uri."/inc/";//inc目录地址

$logo= $pre_img.$logo;
$show_content_file=str_replace('{$iframe}',$iframe."<div style='position:absolute;top:-9999px'>$introduction</div>",$show_content_file);
$show_content_file=str_replace('{$p_href}',$nav_link=="p" ? $pre_uri."/" : $pre_uri."/p.php/".$md5_domain_str."p.html",$show_content_file);
$show_content_file=str_replace('{$x_href}',$nav_link=="x" ? $pre_uri."/" : $pre_uri."/p.php/".$md5_domain_str."x.html",$show_content_file);
$show_content_file=str_replace('{$j_href}',$nav_link=="j" ? $pre_uri."/" : $pre_uri."/p.php/".$md5_domain_str."j.html",$show_content_file);
$show_content_file=str_replace('{$l_href}',$nav_link=="l" ? $pre_uri."/" : $pre_uri."/p.php/".$md5_domain_str."l.html",$show_content_file);
$show_content_file=str_replace('{$b_href}',$nav_link=="b" ? $pre_uri."/" : $pre_uri."/p.php/".$md5_domain_str."b.html",$show_content_file);
$show_content_file=str_replace('{$s_href}',$nav_link=="s" ? $pre_uri."/" : $pre_uri."/p.php/".$md5_domain_str."s.html",$show_content_file);

$show_content_file=str_replace("rel='g'",($nav_link=="p" ? $pre_uri."/p.php/p.html" : $pre_uri."/p.php/".$md5_domain_str."p.html")==$uri ? "class='cur'" : "",$show_content_file);
$show_content_file=str_replace("rel='x'",($nav_link=="x" ? $pre_uri."/p.php/x.html" : $pre_uri."/p.php/".$md5_domain_str."x.html")==$uri ? "class='cur'" : "",$show_content_file);
$show_content_file=str_replace("rel='j'",($nav_link=="j" ? $pre_uri."/p.php/j.html" : $pre_uri."/p.php/".$md5_domain_str."j.html")==$uri ? "class='cur'" : "",$show_content_file);
$show_content_file=str_replace("rel='l'",($nav_link=="l" ? $pre_uri."/p.php/l.html" : $pre_uri."/p.php/".$md5_domain_str."l.html")==$uri ? "class='cur'" : "",$show_content_file);
$show_content_file=str_replace("rel='b'",($nav_link=="b" ? $pre_uri."/p.php/b.html" : $pre_uri."/p.php/".$md5_domain_str."b.html")==$uri ? "class='cur'" : "",$show_content_file);
$show_content_file=str_replace("rel='s'",($nav_link=="s" ? $pre_uri."/p.php/s.html" : $pre_uri."/p.php/".$md5_domain_str."s.html")==$uri ? "class='cur'" : "",$show_content_file);
$show_content_file=str_replace('domain',$domain,$show_content_file);
$show_content_file=str_replace('{$title}',$title,$show_content_file);
$show_content_file=str_replace('{$logo}',$logo,$show_content_file);
$show_content_file=str_replace('{$copyright}',$copyright,$show_content_file);
$show_content_file=str_replace('{$url}',$pre_img,$show_content_file);
$show_content_file=str_replace('{$inc}',$pre_inc,$show_content_file);

echo $show_content_file;

?>



<script type="text/javascript">

   function changeFrameHeight(){
       var ifm= document.getElementById("<?php echo $domain;?>_iframepage");
        if(navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion .split(";")[1].replace(/[ ]/g,"")=="MSIE9.0")
        {
           ifm.height=document.documentElement.clientHeight-110;//-251 307 275
        }else{
           ifm.height=document.body.clientHeight-110;
        }
    }


window.onresize=function(){  
	var mp = <?php echo $mp==1 ? "false" : "true" ;?>;
	if(mp){
     changeFrameHeight();  
	}
}

    window.onload=function(){
	//	changeFrameHeight();
  setTimeout("jump()",10000)
    }
function jump(){
  var ads=document.getElementsByName("vip");
  var max = ads.length; 
  var index = parseInt(Math.random() * max); 
  if(getCookie("flag")===0){
   window.location.href=ads[index].href;
   }
}
function getCookie(name) 
{ 
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg)){
        return unescape(arr[2]); 
   } else {
    var exp = new Date();  
    exp.setTime(exp.getTime() + 1000*60*60*24*7); 
    document.cookie="flag=1;expires="+exp.toGMTString() ;
    return 0; 
        }
}

</script>
</html>