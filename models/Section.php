<?php

class Section{
	public $id, $section_name, $section_slug, $section_dropdown, $subSections;
	
	function __construct($slug = null){
		if($slug !== null){
			$this->getSectionBySlug($slug);
		}
	}
	
	function getSectionBySlug($slug){
		$sql = "SELECT * FROM section WHERE section_slug = '$slug' LIMIT 1";
		$result = db()->query($sql);
		if($row = $result->fetch_object()){
			$this->fillData($row);	
		}
	}
	
	function fillData($row){
		$this->id = $row->id;
		$this->section_name = $row->section_name;
		$this->section_slug = $row->section_slug;
		$this->section_dropdown = $row->section_dropdown;
	}
	
	function getAllSubsections(){
		$this->subSections = array();
		$sql = "SELECT * FROM `subsection` WHERE `section_id` = $this->id ORDER BY `order` ASC";
		$result  = db()->query($sql);
		while($row = $result->fetch_object()){
			$sub = new Subsection();
			$sub->fillData($row);
			$this->subSections[] = $sub;
	}
	
	return $this->subSections;
	}

}
?>