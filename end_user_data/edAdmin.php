<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
if(isset($_SESSION['cbtAdminID'])){
	unset($_SESSION['cbtAdminID']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ambulatory Practice: Confidence-Based Testing Tool</title>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
function init(){
	setUpEvents();
}
function setUpEvents(){
	$('#subBtn').click(function(){
		var tempObj = new Object();
		tempObj['fscore']='8890092';
		tempObj['n_Relay']='admin_74448s';
		tempObj['nRelay']=$('#uname').val();
		tempObj['wscore']='001ts';
		$.post('processors/aIncoming.php',tempObj,uLoadResult,"json");
	})
	$('#uname').keypress(function(e){
		if(e.keyCode==13){
			var tempObj = new Object();
			tempObj['fscore']='8890092';
			tempObj['n_Relay']='admin_74448s';
			tempObj['nRelay']=$('#uname').val();
			tempObj['wscore']='001ts';
			$.post('processors/aIncoming.php',tempObj,uLoadResult,"json");
		}
	})
}
function uLoadResult(result){
	if(result=='userFail'){
		var errorMessage = '<p id="err">Username "' + $('#uname').val() + '" not found, please try again</p>';
		$('#loginContainer').append(errorMessage);	
	} else {
		window.location.href="cbt_control_panel/FB_App/CBT_Control_Panel.html";
	}
}
$("document").ready(init);
</script>

<style type="text/css">
p{
	margin:0px;	
}
#mainPageWrapper{
	width:600px;
	margin:auto;
	border:solid 1px #CCC;	
}
#loginContainer{
	margin-top:50px;	
}
.subBtn{
	width:65px;
	border:solid 1px black;
	cursor:pointer;
	float:left;	
	text-align:center;
}
.adminLoginTxt{
	float:left;
	width:200px;
	background-color:#F5F5F5;
	border:solid 1px #999;
	margin-right:10px;
	text-align:center;
}
#err{
	width:100%;
	color:#F00;
	text-align:center;	
}
</style>
</head>

<body>
<div id="mainPageWrapper">
	<div id="loginContainer">
    	<p class="adminLoginTxt">Administrator Login</p>
		<input id="uname" type="text" style="float:left; width:200px; margin-right:10px;" />
        <div id="subBtn" class="subBtn">Submit</div>
        <div style="clear:both"></div>
	</div>
</div>
</body>
</html>