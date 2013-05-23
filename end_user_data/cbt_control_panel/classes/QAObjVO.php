<?php
class QAObjVO{
	
	public $testQuestionVO;
	public $ansObjArry;
	
	public function __construct(){
		$this->testQuestionVO = new TestQuestionVO();
		$this->ansObjArry = array();
	}
	
}
?>
