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
		if (!preg_match("/^[A-Za-z0-9]{4,12}$/",$url)){
			if (strlen($url)>12)
				$msg = '亲，我是短地址，太长了我受不哦，长度不要超过12';
			elseif (strlen($url)<4)
				$msg = '亲，我只是名叫短地址，但太短小我不接受哟，长度不要小于4';
			else
				$msg = '亲，短地址只允许26个英文字母或数字组合哦。';
		}
	    $url = "http://".$option['domain']."/".$url;
	}
	if ($msg == '') {
		$re = $murl->set($sourceurl,$url);
		if (preg_match("/^http\:\/\/".$option['domain']."/", $re)) {
			$shorturl = str_replace("http://".$option['domain']."/",'',$re);
			$msg ='生成结果：'. $re.' <br/><img src="qrcode.php?url='.$re.'" />';
		} else {
			$shorturl = trim($_POST['shorturl']);
			$msg = '亲，你来迟了，此短地址已有所属';
		}
	}
}else{
	$msg = '请输入源地址';
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $option['domain'];?>短地址生成</title>
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
    	<li class="active"><a href="#">短地址生成</a></li>
    	<li><a href="edit.php">修改源地址</a></li>
    </ul>
<div class="control-group">
 <label class="control-label" for="url">源地址：</label>
 <div class="controls">
	<input class="input-xxlarge" required="required" type="url" name="url" id="url" placeholder="输入源地址" value="<?php echo $sourceurl; ?>" />
 </div>
</div>
<div class="control-group info">
 <label class="control-label" for="shorturl">短地址：</label>
 <div class="controls">
 	<div class="input-prepend">
	<span class="add-on">http://<?php echo $option['domain'];?>/</span><input class="input-large" type="text" name="shorturl" id="shorturl" value="" />
	</div>
	<span class="help-inline">可输入自定义英文和数字，长度限制4-12，留空将自动生成</span>
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