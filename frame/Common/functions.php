<?php
/**
 * 错误处理机制
 */
function handleError($erron,$message,$filename,$linenum){
	$errors=array("1"=>"致命的运行时错误","2"=>"运行时警告","4"=>"解析错误","8"=>"NOTICE");
	if(APP_DEBUG==2){
		echo "<strong>ERROR_TYPE:</strong> {$errors[$erron]} <br />";
		echo "<strong>ERROR_MESSAGE:</strong> $message <br />";
		echo "<strong>ERROR_FILE:</strong> $filename <br />";
		echo "<strong>ERROR_LINE:</strong> $linenum <br />";
	}else{
		$body=<<<EOT
		type:$erron
		message:$message,
		file:$filename,
		line:$linenum,
		-------------------------------------------------------
EOT;
		$smarty=new Smarty();
		$smarty->template_dir =ROOT_PATH."/webroot";//设置模板目录
		if(SINAAPP){
			$mail = new SaeMail();
			$mail->quickSend(
					"985867294@qq.com" ,
					"程序错误" ,
					"$body" ,
					"lirongfei985@163.com" ,
					"jqssjjsw123"
			);
			$smarty->compile_dir ="saemc://Cache/complies";
			$smarty->cache_dir ="saemc://Cache/tpl";
		}else{
			$smarty->compile_dir =APP_PATH."/Cache/complies";
			$smarty->cache_dir =APP_PATH."/Cache/tpl";
		}
		error_log($body,3,APP_PATH."/error.log");
      	if($erron=="25"||$erron=="40"){
	        if(file_exists(APP_PATH."/Lib/Tpl/error.php")){
	        	$smarty->display(APP_PATH."/Lib/Tpl/error.php");
	        }else{
	        	$smarty->display(PL_PATH."/Lib/Tpl/error.php");
	        }
      	}
      	send_error($body);
	}
}
//控制台输出
function xprint($string){
	static $of;
	if(empty($of)){
		$of=fopen(PL_PATH."/Debug/phpdebug.sql", "w+");
	}
	fwrite($of,"$string\n");
}
//通过邮件发送错误通知
function send_error($error){
	$error=preg_replace("/[ \n\r\t-]/", "", $error);
	$error=urlencode($error);
	$last=strrpos(dirname(__FILE__),"\\");
	$str=substr(dirname(__FILE__),0,$last);
	$last=strrpos($str,"\\");
	$path=substr(dirname(__FILE__),$last+1);
	$path=preg_replace("/\\\\/", "/", $path);
	echo '<!doctype html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">';
	echo 'var img=new Image();img.src="'.$path.'/../Debug/senderror.php?error_message='.$error.'";';
	echo '</script>';
}
?>