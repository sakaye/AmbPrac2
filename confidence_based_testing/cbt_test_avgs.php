<?php session_start();
require('dbConnect.php');
include('tableDataGrabber.php');
require('classes/EndUserVO.php');
require('classes/EndUserEducationVO.php');
//$_POST['edID']=1;
$edID = $_POST['edID'];

function avgThis($theArry){
	$theAvg=0;
	if(count($theArry)!=0){
		for($i=0;$i<count($theArry);$i++){
			$theAvg+=$theArry[$i];
		}
		$theAvg = $theAvg/count($theArry);
	}
	return $theAvg;
}
if(isset($_POST['avgs'])){
		//public function getAvgScores($edID){
		//require CBT_CONN;
		$avgQ = 'SELECT * FROM end_user_education WHERE eue_ed_link = '. $edID;
		$avgQResult = $db->query($avgQ);
		$preAvgArry = array();
		$postAvgArry = array();
		$saAvgArry = array();
		while($row = $avgQResult->fetch_assoc()){
			if($row['eue_pre_score']!=5000){
				array_push($preAvgArry,$row['eue_pre_score']);
			}
			if($row['eue_post_score']!=5000){
				array_push($postAvgArry,$row['eue_post_score']);
			}
			if($row['eue_sa_score']!=5000){
				array_push($saAvgArry,$row['eue_sa_score']);
			}
		}
		$preAvg = avgThis($preAvgArry);
		$postAvg = avgThis($postAvgArry);
		$saAvg = avgThis($saAvgArry);
		
		$tempArry = array();
		$tempArry[0] = round($saAvg);
		$tempArry[1] = round($preAvg);
		$tempArry[2] = round($postAvg);
		
		//$totalAvg = ($preAvg + $postAvg + $saAvg) / 3;
		//$totalAvg = round($totalAvg);
		//return $totalAvg;
		echo json_encode($tempArry);
	//}
	

}
?>
