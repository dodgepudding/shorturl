<?php
require 'include/QRcode.class.php';
if ($_GET['url']) echo QRcode::png($_GET['url'],false,0,6,2);
?>