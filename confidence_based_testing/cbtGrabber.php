<?php
require('dbConnect.php');
require('classes/EducationVO.php');
require('classes/EndUserVO.php');
require('classes/EducationDisplayObjVO.php');
require('classes/TopicAreaVO.php');
require('classes/CBTCompletionsVO.php');

function grabTAAdmin($adminID){
	global $tempAdminArry;
	for($i=0; $i<count($tempAdminArry);$i++){
		if($tempAdminArry[$i]->nuid == $adminID){
			$tempAdmin = $tempAdminArry[$i];
		}
	}
	return $tempAdmin;
}
function grabTopicAreas($taID){
	global $tempTopicAreaArry;
	$tempTopicArea='';
	for($i = 0; $i<count($tempTopicAreaArry);$i++){
		if($tempTopicAreaArry[$i]->tta_id == $taID){
			$tempTopicArea = $tempTopicAreaArry[$i]->tta_topic;	
		}
	}
	return $tempTopicArea;
}
function grabNumCompletions($edID){
	require DB_CONN;
	$numCompQ = 'SELECT *, DATE_FORMAT(cbtc_last_comp,"%a, %b, %D %Y, %r") as lastCompFrm FROM cbt_completions WHERE cbtc_ed_link='. $edID . ' LIMIT 1';
	$numCompQResult = $db->query($numCompQ);
	if($numCompQResult->num_rows!=0){
		$tempCompObj = $numCompQResult->fetch_object("CBTCompletionsVO");
	} else {
		$tempCompObj = new CBTCompletionsVO();
		$tempCompObj->cbtc_comp_count=0;
		$tempCompObj->lastCompFrm = 'No Completions';
	}
	return $tempCompObj;
}
$topicAreasQ = 'SELECT * FROM test_topic_areas';
$topicAreasQResult = $db->query($topicAreasQ);
$tempTopicAreaArry = array();
while($row = $topicAreasQResult->fetch_object("TopicAreaVO")){
	array_push($tempTopicAreaArry,$row);
}

$edQ = 'SELECT *, DATEDIFF(NOW(),et_date_created) AS daycount FROM education_table WHERE et_live_on_web="true" ORDER BY et_title';
$edQResult = $db->query($edQ);
$tempEdArry = array();
while($row = $edQResult->fetch_object("EducationVO")){
	$row->topicArea = grabTopicAreas($row->et_topic_area_link);
	array_push($tempEdArry,$row);
}

$tAdminQ = 'SELECT * FROM end_users WHERE eu_access_level="tEditor"';
$tAdminQResult = $db->query($tAdminQ);
$tempAdminArry = array();

while($row = $tAdminQResult->fetch_object("EndUserVO")){
	array_push($tempAdminArry,$row);	
}

$tempEdDisplayObjArry = array();

for($i=0; $i<count($tempEdArry);$i++){
	$tempEdDisplayObjVO = new EducationDisplayObjVO();
	$tempEdDisplayObjVO->edVO = $tempEdArry[$i];
	$tempEdDisplayObjVO->tAdminVO = grabTAAdmin($tempEdArry[$i]->et_test_author_link);
	$tempEdDisplayObjVO->completionsObj = grabNumCompletions($tempEdDisplayObjVO->edVO->et_id);
	array_push($tempEdDisplayObjArry,$tempEdDisplayObjVO);
}

echo json_encode($tempEdDisplayObjArry);
?>
