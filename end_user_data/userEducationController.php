<?php
// need to create a session var (when user logs in or creates a new profile) that holds the users nuid/email, then query the user based on the session var
session_start();

define('DB_CONN','dbConnect.php');
require DB_CONN;
require 'EndUserVO.php';
include('endUserController.php');
require('AP_Education_Service.php');
require('GroupObj.php');
//require($_SERVER['DOCUMENT_ROOT'] .'/AP_EducationManager/services/classes/QAObject.php');


// - set the education id variable to use to submit completions to db = .php?eductionID=COURSE_ID from index
$edID = 'null';
if(isset($_SESSION['edID'])){
	$edID = $_SESSION['edID'];
}
//-------------------------------------------------------------------
$userID = $_SESSION['end_user_id'];

$endUserObj = new EndUserVO();
$endUserObj = getUserNFO($userID);

$tempEdService = new AP_Education_Service();
$qAArry = array();
$qAArry = $tempEdService->getQAndAllAnswers($edID);



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ambulatory Practice</title>
<style type="text/css">
p{
	margin:0;	
}
.questionContainer{
	border:solid 1px #CCC;
	padding:10px;	
}
.qTitle{
	font-size:22px;
	color:#039;
	font-weight:bold;	
}
.ansWrapper{
	width:650px;
}
.ansGroups{
	width:305px;
	border:solid 1px #999;
	float:left;
	padding-left:4px;
	margin-left:10px;
}
</style>
<script type="text/javascript">
function checkRB(q,el){
		tempVal = document.getElementById(el.id).value;
		if(q == 0){
			document.ansFrm.groupB0[0].checked=false;
			document.ansFrm.groupB0[1].checked=false;
		}
		if(q == 1){
			document.ansFrm.groupB1[0].checked=false;
			document.ansFrm.groupB1[1].checked=false;
		}
		//alert(document.ansFrm.groupB0.length);
}
function setRB(q,group,val){
		document.getElementById(group).value=val;
		if(q==0){
			for(i=0;i<document.ansFrm.q0.length;i++){
				document.ansFrm.q0[i].checked=false;	
			}
		}
		if(q==1){
			for(i=0;i<document.ansFrm.q1.length;i++){
				document.ansFrm.q1[i].checked=false;	
			}
		}
}
</script>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">

function init(){
	//$('.questionContainer:eq(1)').css('border','solid 2px red');
	var rbArry = new Array();
	rbArry = $(':radio').css('background-color','red');
}

$('document').ready(init);
</script>
</head>

<body oncontextmenu="return false;">
<form id="ansFrm" name="ansFrm" method="post" action="">
  <?php
echo $endUserObj->l_name . ', ' . $endUserObj->f_name . ' ' . $endUserObj->m_init . '<br />';

echo $edID . '<br />';
//print_r($qAArry);
for($i = 0; $i<count($qAArry);$i++){
	echo '<div class="questionContainer">';
		echo '<p class="qTitle">' . $qAArry[$i]->question . '</p>';
			echo '<div class="ansGroups">';
			for($j = 0;$j<count($qAArry[$i]->ansArry);$j++){
				echo '<p class="ansSelect"><input type="radio" onclick="checkRB(' . $i . ',this)" value="' . $qAArry[$i]->ansIDArry[$j] . '" name="q' . $i .'" id="q' . $i . '" / > ' . $qAArry[$i]->ansRankArry[$j] . ') ' . $qAArry[$i]->ansArry[$j] . ' - edID=' . $qAArry[$i]->ansIDArry[$j] . ' - corrct? = ' . $qAArry[$i]->correctArry[$j] . '</p>';
			}
		echo '</div>';
		$tempGroupObjA = new GroupObj();
		$tempGroupObjA->group="b";
		$tempGroupObjB = new GroupObj();
		$tempGroupObjB->group="c";
		
		echo '<div class="ansGroups">';
		echo '<input type="radio" name="groupB' . $i . '" value="b0" onclick="setRB(' . $i . ',\'ansGroup' . $i . '\', \'b\')"/>';
		echo '<input type="radio" name="groupB' . $i . '" value="b0" onclick="setRB(' . $i . ',\'ansGroup' . $i . '\', \'b\')"/>';
		echo 'TEST TEST TEST</div>';
		echo '<div class="ansGroups">';
		echo '<input type="radio" name="groupC' . $i . '" value="c0" onclick="setRB(' . $i . ',\'ansGroup' . $i . '\', \'c\')"/>';
		echo 'TEST TEST TEST TEST</div>';
		echo '<input type="hidden" id="ansGroup' . $i . '" value=""/>';
		echo '<div style="clear:both"></div>';
	echo '</div>';
}
//sendEdCompletion($endUserObj->nuid,$edID);
?>
  
 <input type="radio" style="background-color:#F00" />
  </form>
</body>
</html>