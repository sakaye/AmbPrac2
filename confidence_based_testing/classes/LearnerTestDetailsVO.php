<?php
class LearnerTestDetailsVO{
	
	public $endUserVO;
	public $endUserEducationVO;
	
	public function __construct(){
		$this->endUserVO = new EndUserVO();
		$this->endUserEducationVO = new EndUserEducationVO();
	}
}
?>