<?php
abstract class Action{
	protected $smarty;
	protected $group=null;
	public function __construct($group=null){
		if(!empty($group)){
			$this->group=$group;
		}
		$this->smarty=new Smarty();
		$this->smarty->template_dir =ROOT_PATH."/webroot";//设置模板目录 
		if(!SINAAPP){
			$this->smarty->compile_dir =APP_PATH."/Cache/complies";
			$this->smarty->cache_dir =APP_PATH."/Cache/tpl";
		}else{
			$this->smarty->compile_dir ="saemc://Cache/complies";
			$this->smarty->cache_dir ="saemc://Cache/tpl";
		}
		$this->smarty->debugging=false;
		if(APP_DEBUG!=0){
			$this->smarty->caching =0;
		}else{
			$this->smarty->cache_lifetime =60*60*24;
			$this->smarty->caching =1;
		}
        $this->before();
	}
	public function before(){
		
	}
	public function display($file){
		$path="";
		if(!empty($this->group)){
			$path=$this->group."/";
		}
		$this->smarty->display($path.$file);
	}
	public function sendRedirect($url){
		echo "<script type='text/javascript'>location.href='$url';</script>";
	}
	public function after(){
		
	}
	public function _empty(){
		
	}
	public function __destruct(){
		$this->after();
	}
}
?>