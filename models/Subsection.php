<?php

class Subsection{
	public $id, $section_id, $name, $caption, $slug, $URL, $locked;
	public $content = array();
	
	function __construct($slug=null){
		if($slug !== null){
			$this->getSubsectionBySlug($slug);
		}
	}
	
	function getSubsectionBySlug($slug){
		$slug =	db()->real_escape_string($slug);
		$sql = "SELECT * FROM subsection WHERE slug = '$slug' LIMIT 1";
		$result = db()->query($sql);
		if($row = $result->fetch_object()){
			$this->fillData($row);	
		}
	}
	
	function getSubsection($id){
		$sql = "SELECT * FROM subsection WHERE id = $id LIMIT 1";
		$result = db()->query($sql);
		if($row = $result->fetch_object()){
			$this->fillData($row);	
		}
	}
	
	function fillData($row){
		$this->id = $row->id;
		$this->name = $row->name;
		$this->caption = $row->caption;
		$this->section_id = $row->section_id;
		$this->slug = $row->slug;
		$this->URL = $row->URL;
		$this->locked = $row->locked;
	}
	
	function getContent(){
		$this->content = array();
		$sql = "SELECT * FROM content WHERE subsection_id = $this->id";
		$result = db()->query($sql);
		while($row = $result->fetch_object()){
			$content = new Content();
			$content->fillData($row);
			$this->content[] = $content;
		}
		
		return $this->content;
	
	}
	
}
?>