shorturl
========

shorturl using mongoDB, auto generate the short url QRcode, with simple API interface

Requirement
--------
* PHP 5.0+  
* php_mongo.so  

Install
--------
setup rewrite rule redirect to /index.php
for nginx.conf (insert to location scope):
```
	if (!-e $request_filename) {
	   rewrite  ^(.*)$  /index.php?s=$1  last;
	}
```
for apache .htaccess:
```
<IfModule mod_rewrite.c>
	RewriteEngine on
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule ^(.*)$ index.php?s=$1 [QSA,PT,L]
</IfModule>
```

change parameters in config.php
```
array(
	'domain'=>'example.com', // your domain name
	'host'=>'localhost', // your mongoDB host
	'port'=>27017,  //your mongoDB port
	'db'=>'shorturl', //your mongoDB databbase
	'table'=>'url', //table name to store urls
	'sumtable'=>'click', //tabale name to store summary
	'username'=>'', //if mongoDB username and password needed
	'password'=>'', //if mongoDB username and password needed
	'keys'=>'23456789abcdefghijkmnpqrstuvwxyz', //the alphabet for shorturl auto generate
	'keylength'=>6, //the short string length
}
```

* visit http://domain.com/add.php to open control panel


Files
-------
zh-cn/* ##Chinese versioin  
include/shorturl.class.php ##MongoDB Model file  
include/QRcode.class.php  ##QRcode library  
index.php  ##shorturl revert  
add.php ##add shorturl panel  
edit.php ##change url panel  
config.php ##configuration  
qrcode.php ##output qrcode image  
api.php ##API interface  

APIs
-------
Apis below returns 0 for failed.  

* get the visits of shorturl   
`GET api.php?a=count&url={shorturl}`   
return the number

* change the exist shorturl to another long url  
`GET api.php?a=edit&source={new long url}&url={shorturl}`  
return the shorturl if success

* set a new shorturl pointed to long url  
`GET api.php?a=edit&source={long url}&url={shorturl}`  
the url for shorturl param is not required, it will generate a random shorturl if null.it will return the shorturl if success.  

* delete a short url  
`GET api.php?a=del&url={shorturl}`  
return 1 if success

* check for long url  
`GET api.php?url={shorturl}`  
return the long url if success  

Demo
-------
http://s.4wer.com