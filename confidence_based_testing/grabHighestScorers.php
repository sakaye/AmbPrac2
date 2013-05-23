<?php session_start();
require('dbConnect.php');
require('tableDataGrabber.php');
require('classes/HighestScorerVO.php');
require('classes/EndUserVO.php');

$hiScoreQ = 'SELECT f_name, l_name, m_init, title_link, mob_link, eu_total_pts FROM end_users WHERE eu_total_pts>0 ORDER BY eu_total_pts DESC LIMIT 10';
$hiScoreQResult = $db->query($hiScoreQ);

$tempArry = array();
$arryCount=10;
while($row = $hiScoreQResult->fetch_assoc()){
	$tempEUObj='';
	$tempEUObj->f_name = $row['f_name'];
	$tempEUObj->l_name = $row['l_name'];
	$tempEUObj->m_init = $row['m_init'];
	$tempEUObj->eu_total_pts = $row['eu_total_pts'];
	array_push($tempArry,$tempEUObj);
	$arryCount-=1;
}
if($arryCount>0){
	while($arryCount>0){
		$tempEUObj='';
		$tempEUObj->f_name='';
		$tempEUObj->l_name='YOUR NAME HERE';
		$tempEUObj->m_init='';
		$tempEUObj->eu_total_pts="X";
		array_push($tempArry,$tempEUObj);
		$arryCount-=1;	
	}
}
echo json_encode($tempArry);

?>