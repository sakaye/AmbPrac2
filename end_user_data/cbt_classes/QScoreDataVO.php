<?php
class QScoreDataVO{
	
	public $tq_id;
	public $tq_ed_link;
	public $tq_question;
	public $tq_pts_possible;
	public $tq_answer;
	public $tq_topic_area_link;
	public $scoresVOArry;
	
	public function __construct(){
		$this->scoresVOArry = array();
	}
}
?>