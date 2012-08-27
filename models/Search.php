<?php
class Search{

	public $searchTerm;
	
		
	function doSearch(){
		$searchTerm = db()->real_escape_string($_GET['searchTerm']);
		$sql = "SELECT * FROM `content` WHERE MATCH(content.keywords) AGAINST( '$searchTerm*' IN BOOLEAN MODE)";
		$result = db()->query($sql);
		if ($result->num_rows > 0){ //search found rows with matching keywords
			$searchResults = array();
			$i=0;
			while($row = $result->fetch_object()){
				$slug = $this->getURL($row);
				$searchResults[$i] = $this->fillData($row, $slug);
				$i++;
			}
		}
		else { //search found no matching keywords
			$searchResults['error'] = "No matches were found for your search term";
		}
		return $searchResults;
	}
	
	function fillData($row, $slug){
		$data = array(
			'id' => $row->id,
			'subsection_id' => $row->subsection_id,
			'name' => $row->name,
			'slug' => $row->slug,
			'order' => $row->order,
			'description' => $row->description,
			'type' => $row->type,
			'URL' => $row->URL,
			'file_name' => $row->file_name,
			'link' => $slug->section_slug .'/'. $slug->subsection_slug . '/');
	
		return $data;
	}
	
	function getURL($row){
		$sql = "SELECT section.slug AS section_slug, subsection.slug AS subsection_slug FROM `section` JOIN `subsection` ON section.id  = subsection.section_id WHERE subsection.id = '$row->subsection_id' LIMIT 1;";
		$result = db()->query($sql);
		$slug = $result->fetch_object();
		
	return $slug;
	}

}

?>