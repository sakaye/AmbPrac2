<?php

class QAObject{
	
	public $question;
	public $questionAnswer;
	public $qID;
	public $qVal;
	public $ansArry;
	public $correctArry;
	public $ansIDArry;
	public $ansRankArry;
	
	public function __construct(){
		$this->ansArry = array();
		$this->correctArry = array();
		$this->ansIDArry = array();
		$this->ansRankArry = array();
	}
	
	public function addAns($ans,$correct,$aID,$ansRank="z"){
		array_push($this->ansArry,$ans);
		array_push($this->correctArry,$correct);
		array_push($this->ansIDArry,$aID);
		array_push($this->ansRankArry,$ansRank);
	}
	public function setQuestion($ques,$qExplanation,$qID,$qVal){
		$this->question = $ques;
		$this->questionAnswer = $qExplanation;
		$this->qID = $qID;
		$this->qVal = $qVal;
	}
	
}

?>
