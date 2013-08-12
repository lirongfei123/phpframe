<?php
require '../Lib/Handle/PHPFetionHandle.class.php';
$error_message=isset($_GET["error_message"])?$_GET["error_message"]:"hello";
$fetion = new PHPFetionHandle('15982427508', 'jqssjjsw123');	// 手机号、飞信密码
$fetion->send('15982427508', $error_message);	// 接收人手机号、飞信内容
?>