<?php
	/* ================= UNCOMMENT FOR TESTING ===================
	define('CBT_CONN','../../../../Conn/conn_end_user_cbt.php');
	require('../classes/EducatorVO.php');
	require('../classes/EducationTableVO.php');
	require('../classes/TopicAreaVO.php');
	require('../classes/EducationSourceVO.php');
	*/
class CBT_CP_Srvc{

	
	public $educationSourceVO;
	public $topicAreaArry;
	
	public function __construct(){
		$this->educationSourceVO = new EducationSourceVO();
		$this->topicAreaArry = array();
	}
	
	public function loadEducatorInfo($adminID){
		$this->grabTopicAreas();
		require CBT_CONN;
		
		$edAdminQ = 'SELECT * FROM end_users WHERE nuid = "' . $adminID . '" AND ' .
				'eu_access_level="tEditor" LIMIT 1';
		$edAdminQResult = $db->query($edAdminQ);
		$edVO = $edAdminQResult->fetch_object("EducatorVO");
		
		$this->educationSourceVO->edAuthor = $edVO;
		$this->educationSourceVO->edTableVOArry = $this->getAllEducation($edVO);
		
		return $this->educationSourceVO;
	}
	
	private function getAllEducation($edVO){
		require CBT_CONN;
		$educationTableQ ='SELECT * FROM education_table';
		$educationTableQResult = $db->query($educationTableQ);
		$tempArry = array();
		
		while($row = $educationTableQResult->fetch_object("EducationTableVO")){
			array_push($tempArry,$row);
		}
		$edObjArry = array();

		for($i=0;$i<count($tempArry);$i++){
			if($tempArry[$i]->et_test_author_link == $edVO->nuid){
				array_push($edObjArry,$tempArry[$i]);
				$tempArry[$i]->et_topic_area_string = $this->grabThisTopicArea($tempArry[$i]->et_topic_area_link);
			}
		}
		
		return $edObjArry;
		
	}
	private function grabTopicAreas(){
		require CBT_CONN;
		$taQ = 'SELECT * FROM test_topic_areas';
		$taQResult = $db->query($taQ);
		while($row = $taQResult->fetch_object("TopicAreaVO")){
			array_push($this->topicAreaArry,$row);
		}
		
	}
	public function grabThisTopicArea($taID){
		$tempTopicArea='';
		for($i=0;$i<count($this->topicAreaArry);$i++){
			if($taID == $this->topicAreaArry[$i]->tta_id){
				$tempTopicArea = $this->topicAreaArry[$i]->tta_topic;
			}
		}
		return $tempTopicArea;
	}
}
//$tempObj = new CBT_CP_Srvc();
//print_r($tempObj->loadEducatorInfo("b327190"));
?>