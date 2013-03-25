<?php
require 'include/shorturl.class.php';
$option = include ('config.php');
$url = trim($_GET['s'],'/');
if($url){
	$murl = new ShortUrl($option);
	if (!preg_match("/^http\:\/\/".$option['domain']."/si", $url)) {
	    $url = "http://".$option['domain']."/".$url;
	}
	$result =$murl->get($url);

	if($result){
		header('Location: '.$result);
	}else {
		header("HTTP/1.1 404 Not Found");
		echo '404 page not found!';
	}

}else{
	header("HTTP/1.1 404 Not Found");
	echo '404 page not found!';
}

?>