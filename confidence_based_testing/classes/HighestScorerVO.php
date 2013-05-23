<?php

class HighestScorerVO{
	
	public $endUserVO;
	public $totalScore;
	
	public function __construct(){
		$this->endUserVO = new EndUserVO();
	}
		
}
	
?>