<?php

class Content{
	public $id, $subsection_id, $name, $slug, $order, $description, $type, $URL, $file_name, $locked;
	public $subContent = array();
	
	function __construct($slug = null){
		if($slug !== null){
			$this->getContentBySlug($slug);
		}
	}
	
	function getContentBySlug($slug){
		$sql = "SELECT * FROM `content` WHERE slug = '$slug' LIMIT 1";
		$result = db()->query($sql);
		if($row = $result->fetch_object()){
			$this->fillData($row);
			if ($this->locked === 'yes'){
				$_SESSION['isLocked'] = true;
			}
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
		$this->locked = $row->locked;
	}
	
	function getSubcontent(){
		if(isset($_SESSION['kp_employee']) && $_SESSION['kp_employee'] == 'yes'){
			$sql = "SELECT * FROM subcontent WHERE content_id = $this->id AND `active` = 'yes' ORDER BY `order` ASC, `name` ASC";
		}
		else{
			$sql = "SELECT * FROM subcontent WHERE content_id = $this->id AND `locked` = 'no' AND `active` = 'yes' ORDER BY `order` ASC, `name` ASC";		
		}
		$result = db()->query($sql);
		while($row = $result->fetch_object()){
			$subContent = new Subcontent();
			$subContent->fillData($row);
			$this->subContent[] = $subContent;
		}
		
		return $this->subContent;
	}
	
	function getACPCMembers(){
		$sql = "SELECT * FROM `acpc_members` WHERE `active` = 'yes' ORDER BY `region_id` ASC, `order` ASC, `last_name` ASC";
		$result = db()->query($sql);
		$regionArr = array();
		while($row = $result->fetch_object()){
			if(!isset($regionArr[$row->region_id])){
		        $regionArr[$row->region_id] = array();
		    }
			$regionArr[$row->region_id][] = array(
								'id' => $row->id,
								'last_name' => $row->last_name,
								'first_name' => $row->first_name,
								'middle_int' => $row->middle_int,
								'degree' => $row->degree,
								'title' => $row->title,
								'phone_number' => $row->phone_number,
								'tie_line' => $row->tie_line,
								'email' => $row->email,
								'discription' => $row->discription,
								'picture' => $row->picture,
								'region_id' => $row->region_id);
			}
		return $regionArr;
	}
	
	function getACPCRegions(){
		$sql = "SELECT * FROM `acpc_regions` ORDER BY `order` ASC, `name` ASC";
		$result = db()->query($sql);
		while($row = $result->fetch_object()){
			$regions[] = array(
								'id' => $row->id,
								'name' => $row->name);
		}
		
		return $regions;
	}
	
	function combineRegions(){
		$regions = $this->getACPCRegions();
		$members = $this->getACPCMembers();
		
		foreach($regions as &$region){
			if (isset($members[$region["id"]])){
				$region["members"] = $members[$region["id"]];
			}
			else {
				$region["members"] = array();
			}
		}
		return $regions;
	}
	
		
	function getConnLinkSections(){
		$sql = "SELECT * FROM `connlink_section` WHERE `active` = 'yes' ORDER BY `order` ASC, `name` ASC";
		$result = db()->query($sql);
		while($row = $result->fetch_object()){
			$sections[] = array(
								'id' => $row->id,
								'name' => $row->name);
		}
		return $sections;
	}
	
	function getConnLinkLinks(){
		if (isset($_SESSION['kp_employee']) && $_SESSION['kp_employee'] == 'yes'){
			$sql = "SELECT * FROM `connlink_links` WHERE `active` = 'yes' ORDER BY `connlink_section_id` ASC, `order` ASC, `name` ASC";
		}
		else {
			$sql = "SELECT * FROM `connlink_links` WHERE `active` = 'yes' AND `locked` = 'no' ORDER BY `connlink_section_id` ASC, `order` ASC, `name` ASC";
		}
		$result = db()->query($sql);
		$sectionArr = array();
		while($row = $result->fetch_object()){
			if (!isset($sectionArr[$row->connlink_section_id])){
				$sectionArr[$row->connlink_section_id] = array();
			}
			$sectionArr[$row->connlink_section_id][] = array(
								'id' => $row->id,
								'name' => $row->name,
								'connlink_section_id' => $row->connlink_section_id,
								'url' => $row->url);
		}
		return $sectionArr;
	}
	
	function combineSectionLinks(){
		$sections = $this->getConnLinkSections();
		$links = $this->getConnLinkLinks();
		
		foreach($sections as &$section){
			if(isset($links[$section["id"]])){
				$section["links"] = $links[$section["id"]];
			}
			else {
				$section["links"] = array();
			}
		}
		return $sections;
	}
	
	function getCvtSections(){
		$sql = "SELECT * FROM `tools_resources_cvt_sections` WHERE `active` = 'yes' ORDER BY `name` ASC";
		$result = db()->query($sql);
		while($row = $result->fetch_object()){
			$sections[] = array(
								'id' => $row->id,
								'name' => $row->name,
								'folder_name' => $row->folder_name);
		}
		return $sections;
	}
	
	function getCvts(){
		$sql = "SELECT * FROM `tools_resources_cvts` WHERE `active` = 'yes' ORDER BY `section_id` ASC, `name` ASC";
		$result = db()->query($sql);
		$sectionArr = array();
		while($row = $result->fetch_object()){
			if (!isset($sectionArr[$row->section_id])){
				$sectionArr[$row->section_id] = array();
			}
			$sectionArr[$row->section_id][] = array(
									'id' => $row->id,
									'name' => $row->name,
									'section_id' => $row->section_id,
									'file_name' => $row->file_name);
		}
		return $sectionArr;
	}
	
	function combineSectionCvts(){
		$sections = $this->getCvtSections();
		$cvts = $this->getCvts();
		
		foreach($sections as &$section){
			if(isset($cvts[$section["id"]])){
				$section["cvts"] = $cvts[$section["id"]];
			}
			else {
				$section["cvts"] = array();
			}
		}
		return $sections;
	}
	
	
	
	
	
	
	
	
	
	
}

?>