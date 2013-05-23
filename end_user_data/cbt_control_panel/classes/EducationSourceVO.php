<?php
class EducationSourceVO{
	
	public $edAuthor;
	public $edTableVOArry;
	
	public function __construct(){
		$this->edAuthor = new EducatorVO();
		$this->edTableVOArry = array();
	}
	
}
?>
