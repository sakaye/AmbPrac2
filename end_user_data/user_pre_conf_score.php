<?php session_start();
define('DB_CONN_MCM','dbConnect.php');
require DB_CONN_MCM;

$edID = $_SESSION['edID'];
$userID = $_SESSION['end_user_id'];
$prePost = $_SESSION['pre_post'];
if($prePost!='sa'){
$preConfQ = 'SELECT * FROM end_user_pretest_conf WHERE eupc_end_user_link = "' . $userID . '" AND eupc_ed_link = ' . $edID . ' ORDER BY eupc_id DESC LIMIT 1';
$preConfQResult = $db->query($preConfQ);
$theScore=0;
while($row = $preConfQResult->fetch_assoc()){
	$theScore = $row['eupc_conf_score'];	
}

} else {
	$theScore=5000;
}
echo json_encode($theScore);

?>