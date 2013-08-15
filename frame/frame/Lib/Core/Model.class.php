<?php
abstract class Model{
	protected $table;//表名
	protected $fields=array();//表的所有(初始化字段)
	protected $currentfield;//表的操作字段
	protected $con;
	protected $asql;
	protected $oldSql; 
	public function __construct(){
		if(empty($this->table)){
			$this->table=lcfirst(substr(get_class($this),0,-5));
		}
		$this->con=get_mysqli();
	}
	public function getSql(){
		return $this->oldSql;
	}
	public function query($sql){
		$result=$this->con->query($sql);
		if(gettype($result)=="object"){
			$returnResult=array();
			while ($assoc=$result->fetch_assoc()){
				$returnResult[]=$assoc;
			}
			return $returnResult;
		}
		return $result;
	}
	private function defaultField(){
		$this->currentfield=implode(",",$this->fields);
	}
	public function select(){
		if(empty($this->currentfield)){
			self::defaultField();
		}
		$sql="select $this->currentfield from $this->table $this->asql";
		if(APP_DEBUG==2){
			xprint($sql);
		}
		$result=$this->con->query($sql);
		$currentfield=explode(",",$this->currentfield);
		$returnResult=array();
		while ($assoc=$result->fetch_assoc()){
			$returnResult[]=$assoc;
		}
		$this->clean();
		return $returnResult;
	}
	public function update($update){
		$fields="";
		foreach ($update as $key=>$value){
			$value=self::getSqlValue($value);
			$fields.="$key=$value,";
		}
		$fields=substr($fields,0,-1);
		$sql="update $this->table set $fields $this->asql";
		if(APP_DEBUG==2){
			xprint($sql);
		}
		$this->con->query($sql);
		$this->asql="";
		$this->clean();
		return $this->con->affected_rows;
	}
	public function save($data){
		$filed="";
		$values="";
		foreach ($data as $key=>$value){
			$filed.="$key,";
			$value=self::getSqlValue($value);
			$values.="$value,";
		}
		$filed=substr($filed,0,-1);
		$values=substr($values,0,-1);
		$sql="insert into $this->table($filed) values($values)";
		if(APP_DEBUG==2){
			xprint($sql);
		}
		$this->con->query($sql);
		return $this->con->affected_rows;
	}
	public function delete(){
		$sql="delete from $this->table $this->asql";
		if(APP_DEBUG==2){
			xprint($sql);
		}
		$this->con->query($sql);
		$this->clean();
		return $this->con->affected_rows;
	}
	private function getSqlValue($value){
		if($value=="now()"){
			return "now()";
		}
		return is_int($value)?$value:"'".$value."'";
	}
	//field
	public function field($str){
		$this->currentfield=$str;
		return $this;
	}
	//where
	public function where($str){
		$this->asql.="where $str ";
		return $this;
	}
	//order by
	public function orderby($str,$type="desc"){
		$this->asql.="order by $str $type ";
		return $this;
	}
	//limit
	public function limit($str){
		$this->asql.="limit $str";
		return $this;
	}
	public function clean(){
		$this->oldSql=$this->asql;
		$this->asql="";
		$this->currentfield="";
	}
}

?>