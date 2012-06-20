<?php

class Content{
	public $id, $subsection_id, $content_name, $content_slug, $order, $content_type, $URL, $file_name;
	
	function __construct($slug = null){
		if($slug !== null){
			$this->getContentBySlug($slug);
		}
	}
	
	function getContentBySlug($slug){
		$sql = "SELECT * FROM content WHERE content_slug = '$slug' LIMIT 1";
		$result = db()->query($sql);
		if($row = $result->fetch_object()){
			$this->fillData($row);	
		}
	}
	
	function fillData($row){
		$this->id = $row->id;
		$this->subsection_id = $row->subsection_id;
		$this->content_name = $row->content_name;
		$this->content_slug = $row->content_slug;
		$this->order = $row->order;
		$this->content_type = $row->content_type;
		$this->URL = $row->URL;
		$this->file_name = $row->file_name;
	}
	
}

?>