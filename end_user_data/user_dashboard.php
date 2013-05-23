<?php
// need to create a session var (when user logs in or creates a new profile) that holds the users nuid/email, then query the user based on the session var
session_start();

define('DB_CONN','dbConnect.php');
require DB_CONN;
require('EndUserVO.php');
include('endUserController.php');

// - set the education id variable to use to submit completions to db
$edID = 'null';
if(isset($_SESSION['edID'])){
	$edID = $_SESSION['edID'];
}
//-------------------------------------------------------------------
$userID = $_SESSION['end_user_id'];

$endUserObj = new EndUserVO();
$endUserObj = getUserNFO($userID);

function wrapThese($label,$content){
	$theString = '<p class="lbl">' . $label . '</p><p class="lblContent">' . $content . '</p><div style="clear:both" />';
	return $theString;	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ambulatory Practice</title>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
function init(){
	$('#preloadImages').hide();
	
	$('#btnContinue').click(function(){
		window.location.href="testCreator.php";
	})
	$('#btnContinue').mouseover(function(){
		$(this).addClass("btnContinueClssHiLight");
	})
	$('#btnContinue').mouseout(function(){
		$(this).removeClass("btnContinueClssHiLight");
	})
	
	$('#btnEditProfile').click(function(){
		window.location.href="user_dashboard_editProfile.php";
	})
	$('#btnEditProfile').mouseover(function(){
		$(this).addClass("btnEditProfileClssHiLight");
	})
	$('#btnEditProfile').mouseout(function(){
		$(this).removeClass("btnEditProfileClssHiLight");
	})
	if(showContinueBtn=='false'){
		$('#btnContinue').hide();	
	}
}
<?php
	if(isset($_SESSION['edID'])){
		echo 'var showContinueBtn="true";';
	} else {
		echo 'var showContinueBtn="false";';
	}
?>
$('document').ready(init);
</script>
<style type="text/css">
body{
	margin:0px;
	background-color:#626262;
}
#pageWrapper{
	width:780px;
	margin:auto;
}
#mainWrapper{
	background-image:url(images/loginBG_dashBoard.jpg);
	background-repeat:no-repeat;
	background-position:0px 0px;
	height:600px;
	width:800px;
	border:solid 1px #626262;
}
#profileWrapper{
	width:550px;
	height:250px;
	margin-left:130px;
	margin-top:100px;
}
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
}
.lbl{
	width:140px;
	float:left;
	background-color:#515151;
	border:solid 1px #999;
	height:22px;
	padding-left:4px;
	font-size:14px;
	margin-bottom:4px;
	color:#FFF;
	text-align:center;
}
.lblContent{
	border-bottom:solid 1px #000;
	padding-top:4px;
	float:left;	
	width:350px;
	padding-left:5px;
	color:#FC0;
	background-color:#1E1E1E;
	height:22px;
	text-align:center;
	line-height: 0.9;
}
p{
	margin:0px;
}
.btnEditProfileClss{
	float:left;
	background-image:url(images/editProfBtn.jpg);
	background-repeat:no-repeat;
	width:140px;
	height:60px;
	cursor:pointer;
}
.btnEditProfileClssHiLight{
	float:left;
	background-image:url(images/editProfBtn_hiLight.jpg);
	background-repeat:no-repeat;
	width:140px;
	height:60px;
	cursor:pointer;
}
.btnContinueClss{
	float:right;
	background-image:url(images/continueBtn.jpg);
	background-repeat:no-repeat;
	width:140px;
	height:60px;
	cursor:pointer;
}
.btnContinueClssHiLight{
	float:right;
	background-image:url(images/continueBtn_hiLight.jpg);
	background-repeat:no-repeat;
	width:140px;
	height:60px;
	cursor:pointer;
}
</style>
</head>

<body>
<div id="pageWrapper">
<div id="mainWrapper">
<div id="profileWrapper">
<?php
echo wrapThese('Username:',$endUserObj->nuid);
echo wrapThese('Name/Title:',$endUserObj->f_name . ' ' . $endUserObj->m_init . '. ' . $endUserObj->l_name . ', ' .$endUserObj->title);
//echo 'KP Emp: ' . $endUserObj->eu_kp_emp . '<br />';
//echo 'Title Link: ' . $endUserObj->title_link . '<br />';
//echo wrapThese('Title:',$endUserObj->title);
if($endUserObj->eu_kp_emp == 'true'){
	//echo 'Area Link: ' . $endUserObj->area_link . '<br />';
	echo wrapThese('Area:',$endUserObj->area);
	//echo 'MOB Link: ' . $endUserObj->mob_link . '<br />';
	echo wrapThese('MOB:',$endUserObj->mob);
}
if($endUserObj->eu_kp_emp == 'false'){
	echo wrapThese('New Facility:',$endUserObj->newFacility);
}

//echo 'Dept Link: ' . $endUserObj->departmentLink . '<br />';
echo wrapThese('Dept:',$endUserObj->dept);
//sendEdCompletion($endUserObj->nuid,$edID);
?>
</div>

<div style="margin-top:25px; border-top:dashed 1px #999; width:500px; padding-top:10px;">
<div id="btnEditProfile" class="btnEditProfileClss"></div>
<div id="btnContinue" class="btnContinueClss"></div>
<div style="clear:both" />
<div id="backToLogin" style="margin-top:25px; font-size:13px;">
<p style="text-align:center"><a href="index.php" style="color:#CCC;">Change User</a></p>
</div>
</div>

</div>
<div id="preloadImages"><img src="images/continueBtn_hiLight.jpg" /><img src="images/editProfBtn_hiLight.jpg" /></div>
</div>
</body>
</html>