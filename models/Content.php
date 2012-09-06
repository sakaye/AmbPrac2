<?php

class Content{
	public $id, $subsection_id, $name, $slug, $order, $description, $type, $URL, $file_name;
	
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
	
	function getACPCMembers(){
		$sql = "SELECT * FROM `acpc_members` WHERE `active` = 'yes' ORDER BY `region_id` ASC, `order` ASC";
		$result = db()->query($sql);
		$i = 0;
		while($row = $result->fetch_object()){
			$members[$i] = array(
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
			$i++;
		}
		
		return $members;
	}
	
	function getACPCRegions(){
		$sql = "SELECT * FROM `acpc_regions` ORDER BY `order` ASC";
		$result = db()->query($sql);
		$i = 0;
		while($row = $result->fetch_object()){
			$regions[$i] = array(
								'id' => $row->id,
								'name' => $row->name);
			$i++;
		}
		
		return $regions;
	}
	
	function getConnLinkSections(){
		$sql = "SELECT * FROM `connlink_section` WHERE `active` = 'yes' ORDER BY `order` ASC";
		$result = db()->query($sql);
		$i = 0;
		while($row = $result->fetch_object()){
			$sections[$i] = array(
								'id' => $row->id,
								'name' => $row->name);
			$i++;
		}
		return $sections;
	}
	
	function getConnLinkLinks(){
		if (isset($_SESSION['kp_employee']) && $_SESSION['kp_employee'] == 'yes'){
			$sql = "SELECT * FROM `connlink_links` WHERE `active` = 'yes' ORDER BY `connlink_section_id` ASC, `order` ASC";
		}
		else {
			$sql = "SELECT * FROM `connlink_links` WHERE `active` = 'yes' AND `locked` = 'no' ORDER BY `connlink_section_id` ASC, `order` ASC";
		}
		$result = db()->query($sql);
		$i = 0;
		while($row = $result->fetch_object()){
			$links[$i] = array(
								'id' => $row->id,
								'name' => $row->name,
								'connlink_section_id' => $row->connlink_section_id,
								'url' => $row->url);
			$i++;
		}
		return $links;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}

?>