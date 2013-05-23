<?php session_start();
define('DB_CONN','../../../../Conn/conn_end_user_cbt.php'); 
require DB_CONN;
require('classes/EducationVO.php');

$compQ = 'SELECT * FROM end_user_education';
$compQResult = $db->query($compQ);
$compArry = array();
while($row = $compQResult->fetch_assoc()){
	array_push($compArry,$row);	
}

function getCompletions($edID){
	global $compArry;
	if(count($compArry)!=0){
		$theResult=0;
		for($i=0; $i<count($compArry);$i++){
			if($edID == $compArry[$i]['eue_ed_link']){
				$theResult +=1;
			}
		}
	} else {
		$theResult = 0;
	}
	return $theResult;
}

$edQ = 'SELECT * FROM education_table ORDER BY et_title';
$edQResult = $db->query($edQ);
$tempArry = array();
while($row = $edQResult->fetch_object("EducationVO")){
	$row->totalCompletions = getCompletions($row->et_id);
	array_push($tempArry,$row);
}

echo json_encode($tempArry);


?>