<?php
class Pl{
	public static function autoload($classname){
		global $paths;
		if(isset($paths["group"])){
			$group=$paths["group"];
		}else{
			$group="";	
		}
		if(substr($classname,-6)=="Action"){
			require_cache(APP_PATH."/Lib/Action/$group/$classname.class.php");
		}else if(substr($classname,-5)=="Model"){
			require_cache(APP_PATH."/Lib/Model/$classname.class.php");
		}else if(substr($classname,-6)=="Handle"){
			if(!require_cache(APP_PATH."/Lib/Handle/$classname.class.php")){
				require_cache(PL_PATH."/Lib/Handle/$classname.class.php");
			}
		}
	}
	//建立项目
	public function build_app(){
		require PL_PATH."/Template/Smarty.class.php";//模板文件类库
		//加载项目配置
		conf(include PL_PATH.'/Conf/conf.php');
		conf(include APP_PATH.'/Conf/conf.php');
		//注册自动导入函数
		spl_autoload_register(array("Pl","autoload"));
		//注册错误处理函数
		set_error_handler("handleError");
	}
	//运行项目
	public static function run(){
		self::build_app();
		//获取url参数
		global $paths;
		$action=$paths["action"];
		$method=$paths["method"];
		$class=ucfirst($action)."Action";
		if(isset($paths["group"])){
			$group=$paths["group"];
		}else{
			$group=null;	
		}
		$now=new $class($group);
		if(method_exists($now,$method)){
			call_user_func(array($now,$method));
		}else{
			$now->display("$method.html");
		}
		
	}
}
?>