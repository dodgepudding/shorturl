<?php
require 'include/shorturl.class.php';

$option = include ('config.php');
$sourceurl = trim(urldecode($_POST['url']));
$url = trim($_POST['shorturl']);
$shorturl = trim($_POST['shorturl']);
if($url){
	$murl = new ShortUrl($option);
	if (!empty($url) && !preg_match("/^http\:\/\/".$option['domain']."/is", $url)) {
	    $url = "http://".$option['domain']."/".$url;
	}
	$oldurl = $murl->get($url);
	if (empty($sourceurl)){
		if (!$oldurl)
			$msg = 'This shorturl does not exist!';
		else
			$sourceurl = $oldurl;
	} else {
		if ($oldurl) {
			if ($oldurl!=$sourceurl)
				$re = $murl->edit($sourceurl,$url);
			$msg ='Change success';
		} else {
			$msg = 'This shorturl does not exist, please generate one first!';
		}
	}
}else{
	if($_POST)
		$msg = 'shorturl can not be null';
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $option['domain'];?> Change shorturl</title>
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
<legend><?php echo $option['domain'];?>Shorturl Service</legend>
    <ul class="nav nav-tabs">
    	<li><a href="add.php">Shorturl Generator</a></li>
    	<li class="active"><a href="#">Change Shorturl</a></li>
    </ul>
<div class="control-group info">
 <label class="control-label" for="shorturl">Shorturl: </label>
 <div class="controls">
 	<div class="input-prepend">
	<span class="add-on">http://<?php echo $option['domain'];?>/</span><input class="input-large" required="required" type="text" name="shorturl" id="shorturl" value="<?php echo $shorturl; ?>" />
	</div>
	<span class="help-inline">please input the exist shorturl</span>
 </div>
</div>
<div class="control-group">
 <label class="control-label" for="url">new source：</label>
 <div class="controls">
	<input class="input-xxlarge" type="url" name="url" id="url" placeholder="new source change here，let it null to fetch the original one" value="<?php echo $sourceurl; ?>" />
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