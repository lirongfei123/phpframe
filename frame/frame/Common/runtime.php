<?php
//创建目录
function build_dir(){
	if(!is_dir(APP_PATH."/Common")){
		mkdir(APP_PATH."/Common");
		file_put_contents(APP_PATH."/Common/common.php","<?php\n?>");
		mkdir(APP_PATH."/Cache");
		mkdir(APP_PATH."/Cache/Tpl");
		mkdir(APP_PATH."/Conf");
		file_put_contents(APP_PATH."/Conf/conf.php",'<?php return array(); ?>');
		mkdir(APP_PATH."/Lib");
		mkdir(APP_PATH."/Lib/Action");
		file_put_contents(APP_PATH."/Lib/Action/IndexAction.class.php","<?php\nclass IndexAction extends Action{\n\tpublic function index(){\n\t\techo \"hell0\"; \n\t}\n}\n?>");
		mkdir(APP_PATH."/Lib/Handle");
		mkdir(APP_PATH."/Lib/Model");
		mkdir(APP_PATH."/Lib/Tpl");
	}
}
build_dir();
//要导入的文件
$import_file=array(
	PL_PATH."/Lib/Core/Action.class.php",//框架拦截器类库
	PL_PATH."/Common/common.php",//框架操作库
	PL_PATH."/Common/functions.php",//框架函数库
	APP_PATH."/Common/common.php",//项目公用函数库
	PL_PATH."/Lib/Core/Pl.class.php",//框架核心类库
	PL_PATH."/Lib/Core/Model.class.php"//框架核心类库
);
//引入文件
foreach($import_file as $file){
	require $file;
}
//建立缓存
function build_app_cache(){
	global $import_file;
	$content="";
	foreach($import_file as $file){
		$content.=compile($file);
	}
	$content='<?php '.$content.'$paths=analysis_url();Pl::run(); ?>';
	file_put_contents(RUNTIME_FILE,$content);
	$content=php_strip_whitespace(RUNTIME_FILE);
	file_put_contents(RUNTIME_FILE,$content);
}
if(APP_DEBUG!=2){
	build_app_cache();
}else{
	if(is_file(APP_PATH."/Cache/~runtime.php")){
		unlink(APP_PATH."/Cache/~runtime.php");
	}
}
$paths=analysis_url();
Pl::run();
?>
