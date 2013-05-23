<?php

class Subsection{
	public $id, $section_id, $name, $description, $slug, $URL, $locked;
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
			if ($this->locked === 'yes'){
				$_SESSION['isLocked'] = true;
			}
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
		$this->description = $row->description;
		$this->section_id = $row->section_id;
		$this->slug = $row->slug;
		$this->URL = $row->URL;
		$this->locked = $row->locked;
	}
	
	function getContent(){
		$this->content = array();
		if(isset($_SESSION['kp_employee']) && $_SESSION['kp_employee'] == 'yes'){
			$sql = "SELECT * FROM content WHERE subsection_id = $this->id AND `active` = 'yes' ORDER BY `order` ASC, `name` ASC";
		}
		else{
			$sql = "SELECT * FROM content WHERE subsection_id = $this->id AND `locked` = 'no' AND `active` = 'yes' ORDER BY `order` ASC, `name` ASC";
		}
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