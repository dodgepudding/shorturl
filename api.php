<?php
require 'include/shorturl.class.php';

$option = include ('config.php');
$act = $_GET['a'];
$murl = new ShortUrl($option);
switch ($act) {
	case 'count':
		$url = $_GET['url'];
		if (!preg_match("/^http\:\/\/".$option['domain']."/is", $url)) {
		    $url = "http://".$option['domain']."/".$url;
		}
		$count = $murl->getsum($url);
		if ($count)
			die(strval($count));
		else
			die('0');
		break;
	case 'edit':
		$surl = $_GET['source'];
		$url = $_GET['url'];
		$re = $murl->edit($surl,$url);
		if ($re)
			die(strval($re));
		else
			die('0');
		break;
	case 'set':
		$surl = $_GET['source'];
		$url = $_GET['url'];
		$re = $murl->set($surl,$url);
		if ($re)
			die(strval($re));
		else
			die('0');
		break;
	case 'del' :
		$url = $_GET['url'];
		$re = $murl->delete($url);
		if ($re)
			die('1');
		else
			die('0');
		break;
	default:
		$url = trim($_GET['url']);
		if (empty($url)) die('0');
		if (!preg_match("/^http\:\/\/".$option['domain']."/is", $url)) {
		    $url = "http://".$option['domain']."/".$url;
		}
		$source = $murl->get($url);	
		if ($source)
			die(strval($source));
		else
			die('0');
		break;	
}