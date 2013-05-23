<?php session_start();
require('AP_Education_Service.php');

$edID = $_SESSION['edID'];

//$edID=25;


$tempAPService = new AP_Education_Service();

$qaArry = array();
$qaArry = $tempAPService->getQAndAllAnswers($edID);

function getAnswers($ansIDArry,$ansArry,$correctArry){
	$tempArry = array();
	for($i=0; $i<count($ansArry);$i++){
		$tempSafeAns = str_replace("'","\'",$ansArry[$i]);
		$tempAnsObj = "{'ansID':" . $ansIDArry[$i] . ",'ans':'" . $tempSafeAns . "','correct':'" . $correctArry[$i] . "'}";
		array_push($tempArry,$tempAnsObj);
	}
	$tempJSONArry = '[' . join(',',$tempArry) . ']';
	return $tempJSONArry;
	
}
$tempQAArry = array();
for($j=0; $j<count($qaArry); $j++){
	$safeQStr = str_replace("'","\'",$qaArry[$j]->question);
	$safeQAnsStr = str_replace("'","\'",$qaArry[$j]->questionAnswer);
	$qaJSONObj = "{'qCount':" . count($qaArry) . ",'qID':" . $qaArry[$j]->qID . ",'question':'" . $safeQStr . "','questionAnswer':'" . $safeQAnsStr . "','ptsPoss':" . $qaArry[$j]->qVal . ",'ansObj':" . getAnswers($qaArry[$j]->ansIDArry,$qaArry[$j]->ansArry,$qaArry[$j]->correctArry) . "}";
	array_push($tempQAArry,$qaJSONObj);
}

$tempJSONArry = array();
$tempJSONArry = '[' . join(',',$tempQAArry) . ']';
//echo "[{'prop1':'prop1Val','prop2':'prop2Val','arryTest':[{'aProp1':'test','aProp2':'test2'}]}]";
echo $tempJSONArry;

?>