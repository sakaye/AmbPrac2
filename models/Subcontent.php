<?php 

class Subcontent{
	public $id, $content_id, $name, $slug, $order, $description, $type, $URL, $file_name, $video_frame, $locked;
	
	function __construct($slug = null){
		if($slug !== null){
			$this->getSubContentBySlug($slug);
		}
	}
	
	function getSubContentBySlug($slug){
		$sql = "SELECT * FROM `subcontent` WHERE slug = '$slug' LIMIT 1";
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
		$this->content_id = $row->content_id;
		$this->name = $row->name;
		$this->slug = $row->slug;
		$this->order = $row->order;
		$this->description = $row->description;
		$this->type = $row->type;
		$this->URL = $row->URL;
		$this->file_name = $row->file_name;
		$this->video_frame = $row->video_frame;
		$this->locked = $row->locked;
	}
	
}	
?>