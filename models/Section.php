<?php

class Section{
	public $id, $name, $slug, $dropdown;
	public $subSections = array();
	
	
	function __construct($slug = null){
		if($slug !== null){
			$this->getSectionBySlug($slug);
		}
	}
	
	function getSectionBySlug($slug){
		$sql = "SELECT * FROM section WHERE slug = '$slug' LIMIT 1";
		$result = db()->query($sql);
		if($row = $result->fetch_object()){
			$this->fillData($row);
		}
	}
	
	function fillData($row){
		$this->id = $row->id;
		$this->name = $row->name;
		$this->slug = $row->slug;
		$this->dropdown = $row->dropdown;
	}
	
	function getSubsections(){
		if(isset($_SESSION['kp_employee']) && $_SESSION['kp_employee'] == 'yes'){
			$sql = "SELECT * FROM `subsection` WHERE `section_id` = $this->id AND `active` = 'yes' ORDER BY `order` ASC, `name` ASC";
		}
		else{
			$sql = "SELECT * FROM `subsection` WHERE `section_id` = $this->id AND `locked` = 'no' AND `active` = 'yes' ORDER BY `order` ASC, `name` ASC";			
		}
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