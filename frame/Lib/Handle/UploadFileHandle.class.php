<?php
class UploadFileHandle{
	private $filename;
	private $filepath;
	private $formname;
	private $info;
	public $finalpath;
	public function __construct($formname,$filename,$filepath,$info=null){
		$this->formname=$formname;
		$this->filename=$filename;
		$this->filepath=$filepath;
		$this->info=$info;
	}
	private function check(){
		$name=$_FILES[$this->formname]["name"];
		preg_match("/\.(.+)$/",$name,$type);
		$type=$type[0];
		$finalpath=$this->filepath."/".$this->filename."$type";
		if($_FILES[$this->formname]["error"]==0){
			if(is_uploaded_file($_FILES[$this->formname]["tmp_name"])){
				if(!SINAAPP){
					if(move_uploaded_file($_FILES[$this->formname]["tmp_name"],$finalpath)){
						$this->finalpath=$finalpath;
						return "ok";
					}
				}else{
					$s = new SaeStorage();
					if(move_uploaded_file($_FILES[$this->formname]["tmp_name"],"saestor://meibang/$finalpath")){
						$this->finalpath=$s->getUrl("meibang",$finalpath);
						return "ok";
					}
				}
			}
		}else{
			return $_FILES[$this->formname]["error"];
		}
	}
	public function upload(){
		$result=self::check();
		if($result=="ok"){
			return "success";
		}else{
			if($result==1||$result==2){
				return empty($this->info)?"请检查文件大小是否超过限制":$this->info;
			}else{
				return "发生网络错误,未成功上传,请重试";
			}
		}
	}
	private function multi_upload(){
		for($i=0,$len=count($_FILES[$this->formname]);$i<$len;$i++){
			$name=$_FILES[$this->formname]["name"][$i];
			preg_match("/\.(.+)$/",$name,$type);
			$type=$type[0];
			$filename=$this->filename.time();
			$finalpath=$this->filepath."/".$filename."$type";
			if($_FILES[$this->formname]["error"][$i]==0){
				if(is_uploaded_file($_FILES[$this->formname]["tmp_name"][$i])){
					if(move_uploaded_file($_FILES[$this->formname]["tmp_name"][$i],$finalpath)){
						$this->finalpath=$finalpath;
						return "success";
					}
				}
			}else{
				$result=$_FILES[$this->formname]["error"][$i];
				if($result==1||$result==2){
					return empty($this->info)?"请检查文件大小是否超过限制":$this->info;
				}else{
					return "发生网络错误,未成功上传,请重试";
				}
			}
		}
	}
}
?>