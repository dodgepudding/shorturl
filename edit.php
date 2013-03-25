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
			$msg = '此短地址不存在';
		else
			$sourceurl = $oldurl;
	} else {
		if ($oldurl) {
			if ($oldurl!=$sourceurl)
				$re = $murl->edit($sourceurl,$url);
			$msg ='修改成功';
		} else {
			$msg = '亲，这个短地址不存在，请移步隔壁生成';
		}
	}
}else{
	if($_POST)
		$msg = '亲，短地址不能为空';
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $option['domain'];?>短地址修改</title>
<meta name="keywords" content="短地址" />
<meta name="description" content="<?php echo $option['domain'];?>短地址生成工具" />
<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
</head>
<body>
<div class="container">
<div class="row">
<div class="span12">
<form class="form-horizontal" method="post">
<legend><?php echo $option['domain'];?>短地址服务</legend>
    <ul class="nav nav-tabs">
    	<li><a href="add.php">短地址生成</a></li>
    	<li class="active"><a href="#">修改源地址</a></li>
    </ul>
<div class="control-group info">
 <label class="control-label" for="shorturl">短地址：</label>
 <div class="controls">
 	<div class="input-prepend">
	<span class="add-on">http://<?php echo $option['domain'];?>/</span><input class="input-large" required="required" type="text" name="shorturl" id="shorturl" value="<?php echo $shorturl; ?>" />
	</div>
	<span class="help-inline">请输入要修改的短地址</span>
 </div>
</div>
<div class="control-group">
 <label class="control-label" for="url">新的源地址：</label>
 <div class="controls">
	<input class="input-xxlarge" type="url" name="url" id="url" placeholder="输入源地址，留空可查询短地址是否存在" value="<?php echo $sourceurl; ?>" />
 </div>
</div>

<div class="control-group">
 <div class="controls">
  	<label class="tips">
 		<?php echo $msg; ?>
 	</label>
	<button type="submit" class="btn">提交</button>
 </div>
</div>
</form>
</div>
</div>
</div>
</body>
</html>