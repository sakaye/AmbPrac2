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
				$searchResults[$i] = $this->fillData($row);
				$i++;
			}
		}
		else { //search found no matching keywords
			$searchResults['error'] = "No matches were found for your search term";
		}
	return $searchResults;
	}
	
	function fillData($row){
		$data = array(
			'id' => $row->id,
			'subsection_id' => $row->subsection_id,
			'name' => $row->name,'slug' => $row->slug,
			'order' => $row->order,
			'description' => $row->description,
			'type' => $row->type,
			'URL' => $row->URL,
			'file_name' => $row->file_name);
	
		return $data;
	}

}

?>