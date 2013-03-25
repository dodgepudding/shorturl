<?php
require 'include/shorturl.class.php';
$option = include ('config.php');
$sourceurl = trim(urldecode($_POST['url']));
$shorturl = '';
if($sourceurl){
	$murl = new ShortUrl($option);
	$url = trim($_POST['shorturl']);
	$msg = '';
	if (!empty($url) && !preg_match("/^http\:\/\/".$option['domain']."/is", $url)) {
		if (!preg_match("/^[A-Za-z0-9\-_]{4,12}$/",$url)){
			if (strlen($url)>50)
				$msg = 'short string can not longer than 50';
			elseif (strlen($url)<3)
				$msg = 'short string can not shorter than 3';
			else
				$msg = 'only accept for A-Z,a-z,0-9, or - and _, other character is not allow';
		}
	    $url = "http://".$option['domain']."/".$url;
	}
	if ($msg == '') {
		$re = $murl->set($sourceurl,$url);
		if (preg_match("/^http\:\/\/".$option['domain']."/", $re)) {
			$shorturl = str_replace("http://".$option['domain']."/",'',$re);
			$msg ='result：'. $re.' <br/><img src="qrcode.php?url='.$re.'" />';
		} else {
			$shorturl = trim($_POST['shorturl']);
			$msg = 'this short url exists. please change another. ';
		}
	}
}else{
	$msg = 'please input the source url';
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $option['domain'];?> Shorturl Generator</title>
<meta name="keywords" content="Shorturl" />
<meta name="description" content="<?php echo $option['domain'];?>Shorturl Generator" />
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
</head>
<body>
<div class="container">
<div class="row">
<div class="span12">
<form class="form-horizontal" method="post">
<legend><?php echo $option['domain'];?> Shorturl Service</legend>
    <ul class="nav nav-tabs">
    	<li class="active"><a href="#">Shorturl Generator</a></li>
    	<li><a href="edit.php">Change Shorturl</a></li>
    </ul>
<div class="control-group">
 <label class="control-label" for="url">Source: </label>
 <div class="controls">
	<input class="input-xxlarge" required="required" type="url" name="url" id="url" placeholder="Long URL" value="<?php echo $sourceurl; ?>" />
 </div>
</div>
<div class="control-group info">
 <label class="control-label" for="shorturl">Shorturl：</label>
 <div class="controls">
 	<div class="input-prepend">
	<span class="add-on">http://<?php echo $option['domain'];?>/</span><input class="input-large" type="text" name="shorturl" id="shorturl" value="" />
	</div>
	<span class="help-inline">you can define your shorturl or let it null to auto-generate</span>
 </div>
</div>
<div class="control-group">
 <div class="controls">
  	<label class="tips">
 		<?php echo $msg; ?>
 	</label>
	<button type="submit" class="btn">submit</button>
 </div>
</div>
</form>
</div>
</div>
</div>
</body>
</html>