<?php

class EndUserEducationVO{
	
	public $eue_id;
	public $eue_user_link;
	public $eue_ed_link;
	public $eue_pre_score;
	public $eue_post_score;
	public $eue_pre_test_date;
	public $eue_post_test_date;
	public $eue_complete_date;
	public $bypass;
	public $eue_sa_score;
	public $eue_sa_test_date;
	public $endUserVO;
	
	public $preTestDate;
	public $postTestDate;
	public $saTestDate;
	public $testTitle;
	public $testDescript;
	public $testAuthor;
	
	public function __construct(){
		$this->endUserVO = new EndUserVO();	
	}
		
}

?>