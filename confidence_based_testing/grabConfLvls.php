<?php session_start();
require('dbConnect.php');
$edID = $_POST['edID'];
//$edID=1;
$confLvlQ = 'SELECT * FROM education_score_details WHERE esd_ed_id = '. $edID . ' ORDER BY esd_pre_post ASC';
$confLvlQResult = $db->query($confLvlQ);
$confLvlArry = array();

while($row = $confLvlQResult->fetch_assoc()){
	array_push($confLvlArry,$row);
}
$preTestConfLvl=0;
$preTestCount=0;
$postTestConfLvl=0;
$postTestCount=0;
$saTestConfLvl=0;
$saTestCount=0;

for($i=0; $i<count($confLvlArry);$i++){
	if($confLvlArry[$i]['esd_pre_post']=='sa'){
		if($confLvlArry[$i]['esd_ans_group']=='setA'){
			$saTestConfLvl+=1;				
		}
		$saTestCount+=1;
	}
	
	if($confLvlArry[$i]['esd_pre_post']=='pre'){
		if($confLvlArry[$i]['esd_ans_group']=='setA'){
			$preTestConfLvl+=1;				
		}
		$preTestCount+=1;
	}
	
	if($confLvlArry[$i]['esd_pre_post']=='post'){
		if($confLvlArry[$i]['esd_ans_group']=='setA'){
			$postTestConfLvl+=1;				
		}
		$postTestCount+=1;
	}
	
}
if($saTestCount>0){
	$saPercent = ($saTestConfLvl/$saTestCount)*100;
} else {
	$saPercent = 'No Data';
}
if($preTestCount>0){
	$prePercent = ($preTestConfLvl/$preTestCount)*100;
} else {
	$prePercent = 'No Data';	
}
if($postTestCount>0){
	$postPercent = ($postTestConfLvl/$postTestCount)*100;
} else {
	$postPercent = 'No Data';	
}

if($saPercent!='No Data'){
	$saPercent = intval($saPercent);
}
if($prePercent!='No Data'){
	$prePercent = intval($prePercent);
}
if($postPercent!='No Data'){
	$postPercent = intval($postPercent);
}

$resultObj='';
$resultObj->standAloneLvl = $saPercent;
$resultObj->preLvl = $prePercent;
$resultObj->postLvl = $postPercent;

echo json_encode($resultObj);

?>