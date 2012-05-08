<?php

class Subsection{
	public $id, $section_id, $subsection_name, $subsection_caption, $subsection_slug, $link, $locked;
	public $content = array();
	
	function __construct($id=null){
		if($id !== null){
			$this->getSubsection($id);
		}
	}
	
	function getSubsectionBySlug($slug){
/* 		$slug =	db()->mysql_real_escape_string($slug); */
		$sql = "SELECT * FROM subsection WHERE subsection_slug = '$slug' LIMIT 1";
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
		$this->subsection_name = $row->subsection_name;
		$this->subsection_caption = $row->subsection_caption;
		$this->section_id = $row->section_id;
		$this->subsection_slug = $row->subsection_slug;
		$this->outside_link = $row->outside_link;
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