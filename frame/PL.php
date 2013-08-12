<?php
defined("APP_DEBUG") or define("APP_DEBUG",0);//debug
defined("SINAAPP") or define("SINAAPP",false);//是否支持新浪云
defined("ROOT_PATH") or define("ROOT_PATH",getcwd());//入口文件的路径
defined("PL_PATH") or define("PL_PATH",dirname(__FILE__));//框架入口文件的路径

defined("COMMON_PATH") or define("COMMON_PATH",APP_PATH."/Common");//项目类库文件路径
defined("CONF_PATH") or define("CONF_PATH",APP_PATH."/Conf");//项目配置文件路径
defined("LIB_PATH") or define("LIB_PATH",APP_PATH."/Lib");//项目类库文件路径
defined("TPL_PATH") or define("TPL_PATH",APP_PATH."/Template");//项目模板文件路径
if(SINAAPP){
	defined("RUNTIME_FILE") or define("RUNTIME_FILE","saemc://Cache/~runtime.php");//定义运行时文件
}else{
	defined("RUNTIME_FILE") or define("RUNTIME_FILE",APP_PATH."/Cache/~runtime.php");//定义运行时文件
}
//定义全局路径解析
$paths=array();
if(APP_DEBUG==0&&is_file(RUNTIME_FILE)){
	require RUNTIME_FILE;
}else{
	require PL_PATH."/Common/runtime.php";
}
?>