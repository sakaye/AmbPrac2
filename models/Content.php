<?php

class Content{
	public $id, $subsection_id, $content_name, $order;
	
	function __construct($id = null){
		if($id !== null){
			$this->getContent($id);
		}
	}
	
	function getContent($id){
		$sql = "SELECT * FROM content WHERE id = $id LIMIT 1";
		$result = db()->query($sql);
		if($row = $result->fetch_object()){
			$this->fillData($row);	
		}
	}
	
	function fillData($row){
		$this->id = $row->id;
		$this->subsection_id = $row->subsection_id;
		$this->content_name = $row->content_name;
		$this->order = $row->order;
	}

}






?>