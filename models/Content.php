<?php

class Content{
	public $id, $subsection_id, $name, $slug, $order, $description, $type, $URL, $file_name;
	
	function __construct($slug = null){
		if($slug !== null){
			$this->getContentBySlug($slug);
		}
	}
	
	function getContentBySlug($slug){
		$sql = "SELECT * FROM content WHERE slug = '$slug' LIMIT 1";
		$result = db()->query($sql);
		if($row = $result->fetch_object()){
			$this->fillData($row);	
		}
	}
	
	function fillData($row){
		$this->id = $row->id;
		$this->subsection_id = $row->subsection_id;
		$this->name = $row->name;
		$this->slug = $row->slug;
		$this->order = $row->order;
		$this->description = $row->description;
		$this->type = $row->type;
		$this->URL = $row->URL;
		$this->file_name = $row->file_name;
	}
	
}

?>