<?php
class TestQuestionsVO{
	
	public $tq_id;
	public $tq_ed_link;
	public $tq_question;
	public $qStatsArry;
	public $tq_og_author;
	
	public function __construct(){
		$this->qStatsArry = array();
	}
	
}
?>
