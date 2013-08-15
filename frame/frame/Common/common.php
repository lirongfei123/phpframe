<?php
// 编译文件
function compile($filename) {
    $content = file_get_contents($filename);
    // 替换预编译指令
    $content = substr(trim($content), 5);
    if ('?>' == substr($content, -2))
        $content    = substr($content, 0, -2);
    return $content;
}
/**
 * 得到数据库
 * Enter description here ...
 */
function get_mysqli(){
	if(isset($GLOBALS['db_con'])){
		return $GLOBALS['db_con'];
	}else{
		$database=conf("DATABASE");
		$GLOBALS['db_con']=new mysqli($database["hostname"],$database["username"],$database["password"],$database["database"]);
		if(mysqli_connect_errno()){
			echo "connon error".mysqli_connect_error();
			exit;
		}
		$GLOBALS['db_con']->query("set names utf8");
		return $GLOBALS['db_con'];
	}
}
/**
 * 优化的require_once
 * @param string $filename 文件地址
 * @return boolen
 */
function require_cache($filename) {
    static $_importFiles = array();
    if (!isset($_importFiles[$filename])) {
        if (file_exists($filename)) {
            require $filename;
            $_importFiles[$filename] = true;
        } else {
            $_importFiles[$filename] = false;
        }
    }
    return $_importFiles[$filename];
}
/**
 * 获取配置参数
 */
function conf($name=null,$value=null){
	static $confs=array();
	if(empty($name)){
		return $confs;
	}else if(is_string($name)){
		if(empty($value)){
			if(isset($confs[$name])){
				return $confs[$name];
			}else{
				return false;
			}
		}else{
			$confs[$name]=$value;
			return true;
		}
	}else if(is_array($name)){
		foreach ($name as $key=>$value){
			$confs[$key]=$value;
		}
		return true;
	}
}
/**
 * 获取url参数
 */
function analysis_url(){
	$get_keys=array_keys($_GET);//得到action参数
	if(isset($get_keys[0])){
		$aurl=$get_keys[0];
	}else{
		$aurl="index!index_html";
	}
	$url=str_replace("_html", "", $aurl);//过滤到后缀名
	//查看有没有get参数
	if(strrpos($url, "?")>0){
		$getparam=substr($url,strrpos($url, "?")+1);
		$getvalue=$_GET["$aurl"];
		$url=substr($url,0,strrpos($url, "?"));
		$tmp=array("$getparam"=>"$getvalue");
		$_GET["$getparam"]=$getvalue;
	}
	$pathinfo_arr=array();
	$paths=explode("!", $url);
	//先得到action
	if(empty($paths[0])){//如果没有action执行空action
		$pathinfo_arr["action"]="Empty";
	}else{
		if(preg_match("/-/",$paths[0])>0){//如果有#隔开,说明有目录
				$actions=explode("-",$paths[0]);
				$pathinfo_arr["group"]=$actions[0];
				$pathinfo_arr["action"]=ucfirst($actions[1]);
		}else{
			$pathinfo_arr["action"]=ucfirst($paths[0]);
		}
	}
	//然后得到方法
	if(empty($paths[1])){
			$pathinfo_arr["method"]="_empty";
	}else{
		$pathinfo_arr["method"]=$paths[1];
	}
	return $pathinfo_arr;
}
?>