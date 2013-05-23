<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
require'EndUserVO.php';
require('getEndUserData.php');
// =========== GRAB SESSION VARS =============================
if(isset($_GET['review'])){
	$userID = $_GET['uID'];
	$prePost = $_GET['pre_post'];
	$edID = $_GET['edID'];
	$edTitle = $_GET['edTitle'];
	$_SESSION['edID'] = $edID;
	$_SESSION['end_user_id'] = $userID;
	$_SESSION['pre_post'] = $prePost;
} else {

	if(isset($_SESSION['inTest'])){
	$userID = $_SESSION['end_user_id'];
	$prePost = $_SESSION['pre_post'];
	$edID = 'null';
	if(isset($_SESSION['edID'])){
		$edID = $_SESSION['edID'];
	}
	$edTitle = $_SESSION['edTitle'];
	}
}

// =====================================
$endUserObj = getUserNFO($userID);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AmbulatoryPractice.org - Confidence-Based Testing Tool</title>
<script type="text/javascript" src="jquery.min.js"></script>
<script src="swfobject_modified.js" type="text/javascript"></script>
<script type="text/javascript">
// ============= classes ====================== //
function UserScore(){
	this.preTestDate = '';
	this.postTestDate = '';
	this.preTestScore ='';
	this.postTestScore = '';
	this.saScore = '';
	this.testType = '';
}
// =========================================== //
function init(){
	var tempObj = new Object();
	tempObj['endUserScore'] = 'true';
	$.post('endUserEdController.php',tempObj,testScoreResults,"json");
	$('#detailsContainer').hide();
	$('#resultFilters').hide();
	$('#detailsTitle').hide();
	$('#contBtn').hide();
	$('#contBtn').click(function(){
		window.location.href="cbtResponse.php?edID=<?php echo $edID . '&pre_post='.$prePost;?>";
	})
}
function testScoreResults(result){
	var userScoreData = new UserScore();
	userScoreData.preTestDate = result[0].userPreTestDate;
	userScoreData.postTestDate = result[0].userPostTestDate;
	userScoreData.preTestScore = result[0].userPreScore;
	userScoreData.postTestScore = result[0].userPostScore;
	userScoreData.saScore = result[0].userSAScore;
	userScoreData.testType = result[0].testType;
	displayUserScore(userScoreData);
	grabTestDetails();
}
function displayUserScore(theData){
	if(theData.testType == "post"){
		scoreForFlash = theData.postTestScore.toString();
		scoreForFlashPre = theData.preTestScore.toString();
	} else if(theData.testType == "sa"){
		scoreForFlash = theData.saScore.toString();
		scoreForFlashPre = '666';
	}
	//var postTScoreDiv='<div class="postTScore">' + theData.postTestScore + '%</div>';
	//$(postTScoreDiv).appendTo('#scoreContainer');
	//var preTScoreDiv='<div class="preTScore">pre-test score = ' + theData.preTestScore + '</div>';
	//$(preTScoreDiv).appendTo('#detailedStats');
}

function grabTestDetails(){
	$.getJSON('QAGrabber.php?edID=<?php echo $edID; ?>','',qaResult);
	var tempObj2 = new Object();
	tempObj2['endUserScore'] = 'true';
	$.post('user_pre_conf_score.php',tempObj2,preConfResult,"json");
}
function preConfResult(result){
	// if result equals 5000 then this was a stand alone test and thus has no pre test data
	preConfScoreForFlash = result;
}
function qaResult(result){
	dataSourceAC = result;
	var counter=0;
	var questionCounter =1;
	totalACount = result[counter].qCount;
	$(result).each(function(){
		// ========== create new container objects ==============
		var tempDivObj = createQADetailContainer(result[counter].qID);
		$(tempDivObj).appendTo('#detailsContainer');
		var qCountObj = '<p class="qCountClss">' + questionCounter + ' of ' + result[counter].qCount + '</p>';
		$(qCountObj).appendTo('#qCount_'+result[counter].qID);
		questionCounter+=1;
		var qObj = '<p class="testQClss">' + result[counter].question + '</p>';
		$(qObj).appendTo('#testQuestionContainer_' + result[counter].qID);
		var qAnsObj = '<p style="margin:0px;">'+ result[counter].questionAnswer +'</p>';
		$(qAnsObj).appendTo('#ansExplanation_' + result[counter].qID);
		// ============== retreive all possible answers ==========
		var counter2=0;
		for(var i=0;i<result[counter].ansObj.length;i++){
			// ======== set style for correct ans =====
			var correctIndicator='';
			if(result[counter].ansObj[counter2].correct=='true'){
				correctIndicator=' style="background-image:url(images/correctBg.jpg); background-repeat:repeat-x; background-position:top left; color:#FFF; font-weight:bold; padding:2px;" ';
			}
			//=========================================
			var tempAnsString = '<li id="tAnsID_' + result[counter].ansObj[counter2].ansID + '"' + correctIndicator + '>' + result[counter].ansObj[counter2].ans + '</li>';
			$(tempAnsString).appendTo('#ansUL_' + result[counter].qID);
			counter2+=1;
		}
		//========================================================
		
		counter+=1;
	})
	grabEndUserDetails();
}
function grabEndUserDetails(){
	var tempObj = new Object();
	tempObj['endUserDetails'] = 'true';
	$.post('endUserEdController.php',tempObj,endUserTestDetails,"json");
}
function endUserTestDetails(result){
	resultObj = result;
	
	var counter=0;
	var confidenceCounter = 0;
	$(result).each(function(){
		var tempConfLvlHolder='';
		//var tempAnsObj = '<p id="userAns_' + result[counter].qID + '" class="usersAnswer">Question: ' + result[counter].question + '[qID=' + result[counter].qID + '], ' + result[counter].answer + ', ' + result[counter].prePost + ', ' + result[counter].ansGroup + '</p>';
		var tempAnsObj = '<p id="userAns_' + result[counter].qID + '" class="usersAnswer">' + result[counter].answer + '</p>';
		$(tempAnsObj).appendTo('#endUserAnsContainer_'+ result[counter].qID);		
		// ======= append the correct style [confidnet, uncertain, do know know] to the container
		if(result[counter].ansGroup=='setA'){
			confidenceCounter +=1;
			$('#endUserAnsContainer_'+ result[counter].qID).addClass('userAnswerContainer_confident');
			detailsConfLvlCounter("confident"); // send to flash
			tempConfLvlHolder="confident";
		} else if(result[counter].ansGroup=='setB'){
			confidenceCounter +=.5;
			$('#endUserAnsContainer_'+ result[counter].qID).addClass('userAnswerContainer_unCertain');
			detailsConfLvlCounter("uncertain"); // send to flash
			tempConfLvlHolder="uncertain";		
		} else {
			$('#endUserAnsContainer_'+ result[counter].qID).addClass('userAnswerContainer_doNotKnow');
			detailsConfLvlCounter("doNotKnow"); // send to flash
			tempConfLvlHolder="doNotKnow";
		}
		// =====================================================================================
		// =========== append score response ===================================================
		if(result[counter].score>0){
			$('#ansDisplay_'+ result[counter].qID).addClass('answerCorrect');
			$('#ansDisplay_'+ result[counter].qID).css('color','#0F0');
			detailsAnsCorrectCounter(tempConfLvlHolder);// send to flash
			var plusMinus='+';
		} else if(result[counter].score==0){
			$('#ansDisplay_'+ result[counter].qID).addClass('answerNeutral');
			$('#ansDisplay_'+ result[counter].qID).css('color','#FFF');
			var plusMinus='';
		} else {
			$('#ansDisplay_'+ result[counter].qID).addClass('answerInorrect');
			$('#ansDisplay_'+ result[counter].qID).css('color','red');
			var plusMinus='';
			detailsAnsIncorrectCounter(tempConfLvlHolder);
		}
		// calculate num of corr,incorr,do not know, ect for filter btns
		sendToFilterBtns(result[counter].score,result[counter].ansGroup);
		// =====================================================================================
		//================ append score ==============================
		var tempScoreObj = '<div style="margin:0px; width:75px; text-align:center;">'+ plusMinus + result[counter].score +'</div>';
		$(tempScoreObj).appendTo('#userScore_'+ result[counter].qID);
		// ===========================================================
		counter +=1;
	})
	// ================== calculate confidence level ======================
	confidencePercent = (confidenceCounter / counter)*100;
	confidencePercent = Math.round(confidencePercent);
	//=====================================================================
	grabAllQStats();
	setStyles();
	applyFilterBtnTxt();
}
function grabAllQStats(){
	var qIDArry = new Array();
	for(var i=0;i<dataSourceAC.length;i++){
		qIDArry[i] = dataSourceAC[i].qID;
	}
	
	var qIDObj = new Object();
	qIDObj['qIDArry'] = qIDArry.toString();
	$.post('testStatsProcessor.php',qIDObj,qStatsComplete,"json");
}
function qStatsComplete(result){
	var qStatArry = new Array();
	qStatArry = result;
	for(var i=0; i<qStatArry.length;i++){
		var totalAnswers = Number(qStatArry[i].qss_total_correct) + Number(qStatArry[i].qss_total_incorrect);
		var percentCorrect = (Number(qStatArry[i].qss_total_correct)/Number(totalAnswers))*100;
		var percentIncorrect = (Number(qStatArry[i].qss_total_incorrect)/Number(totalAnswers))*100;
		var percentConfidence = (Number(qStatArry[i].qss_conf_count)/Number(totalAnswers))*100;
		percentCorrect = Math.round(percentCorrect);
		percentIncorrect = Math.round(percentIncorrect);
		percentConfidence = Math.round(percentConfidence);
		
		$('#ansStatsTxt_'+qStatArry[i].qss_q_link).html(qStatArry[i].qss_total_correct + ' of ' + totalAnswers + ' have answered this question correctly = ' + percentCorrect + '%<br /><span style="color:red">' + percentIncorrect + '% answered this question Incorrectly</span><br /><span style="color:#E9E9E9;">' + percentConfidence + '% answered confidently</span>');
	}
}
function sendToFilterBtns(theScore,ansGroup){
	showAllCount+=1;
	if(theScore>0){
		showCorrectCount+=1;	
	}
	if(theScore==0){
		showDoNotKnowCount+=1;	
	}
	if(theScore<0){
		showIncorrectCount+=1;	
	}
	if((theScore<0)&&(ansGroup=='setA')){
		showConfIncorrectCount+=1;
	}
}
function applyFilterBtnTxt(){
	$('#showAll').text('Show All (' + showAllCount + ')');
	$('#showCorrect').text('Show All Correct (' + showCorrectCount + ')');	
	$('#showDoNotKnow').text('Show "Do Not Know" (' + showDoNotKnowCount + ')');	
	$('#showIncorrect').text('Show All Incorrect (' + showIncorrectCount + ')');	
	$('#showConfIncorrect').text('Show All Confidently Incorrect (' + showConfIncorrectCount + ')');	
}
// =========== create ans detail html object ==================
function createQADetailContainer(qID){
	var tempObj = '<div id="testQContainer_' + qID + '">' +
	'<div class="qCountContainer" id="qCount_' + qID + '"></div>' +
	'<div id="testQuestionContainer_' + qID + '"></div>' + 
	'<div id="testAnsContainer_' + qID + '"><ul id="ansUL_' + qID + '" class="ulAnsClss"></ul>' + 
	'</div>' +
	'<div id="endUserAnsContainer_' + qID + '"></div>' +
	'<div id="ansDisplay_' + qID + '"><div id="userScore_'+ qID + '"></div></div>' +
	'<div id="ansStats_' + qID + '" class="ansStatsClss"><p id="ansStatsTxt_' + qID + '" class="ansStatsTxtClss"></p></div>' + 
	'<div style="clear:both"></div>' +
	'<p id="ansExplanation_' + qID + '"></p>' +
	'<div style="clear:both"></div>' +
	'<div class="qSpacer"></div>' +
	'</div>';
	return tempObj;
}
function setStyles(){
	$('[id^=testQContainer]').addClass('tQContainerClss');
	$('[id^=testQuestionContainer]').addClass('tQuestionContainer');
	$('[id^=testAnsContainer]').addClass('tAnsContainer');
	$('[id^=endUserAnsContainer]').addClass('userAnswerContainer');
	$('[id^=tAnsID]').addClass('tAnswers');
	$('[id^=userScore]').addClass('userScoreDisplay');
	$('[id^=ansExplanation]').addClass('ansExplanation');
	setupFilters();
}
function showSpecificResults(resultFilter){
	var resObjCounter = 0;
	$(resultObj).each(function(){
		if(resultFilter=='incorrect'){
			if(resultObj[resObjCounter].score>=0){
				$('#testQContainer_'+resultObj[resObjCounter].qID).hide();
			} else {
				$('#testQContainer_'+resultObj[resObjCounter].qID).show();
			}
		}
		if(resultFilter=='correct'){
			if(resultObj[resObjCounter].score<=0){
				$('#testQContainer_'+resultObj[resObjCounter].qID).hide();
			} else {
				$('#testQContainer_'+resultObj[resObjCounter].qID).show();
			}
		}
		if(resultFilter=='doNotKnow'){
			if(resultObj[resObjCounter].score==0){
				$('#testQContainer_'+resultObj[resObjCounter].qID).show();
			} else {
				$('#testQContainer_'+resultObj[resObjCounter].qID).hide();
			}
		}
		if(resultFilter=='confIncorrect'){
			if( (resultObj[resObjCounter].score<0) && (resultObj[resObjCounter].ansGroup=='setA') ){
				$('#testQContainer_'+resultObj[resObjCounter].qID).show();
			} else {
				$('#testQContainer_'+resultObj[resObjCounter].qID).hide();
			}
		}
		if(resultFilter=='showAll'){
			$('#testQContainer_'+resultObj[resObjCounter].qID).show();
		}
		resObjCounter+=1;
	});
}
function setupFilters(){
	$('#showIncorrect').click(function(){
		filterHiLight('showIncorrect');
		showSpecificResults('incorrect');	
	})
	$('#showCorrect').click(function(){
		filterHiLight('showCorrect');
		showSpecificResults('correct');	
	})
	$('#showAll').click(function(){
		filterHiLight('showAll');
		showSpecificResults('showAll');	
	})
	$('#showDoNotKnow').click(function(){
		filterHiLight('showDoNotKnow');
		showSpecificResults('doNotKnow');	
	})
	$('#showConfIncorrect').click(function(){
		filterHiLight('showConfIncorrect');
		showSpecificResults('confIncorrect');	
	})
}
function filterHiLight(theFilter){
	var filterCounter=0;
	$('#resultFilters').children('[id^=show]').each(function(){
		$(this).removeClass();	
		$(this).addClass('filterBtns');	
	})
	$('#showConfIncorrect').removeClass();
	$('#showConfIncorrect').addClass('filterBtnsConfInc');
	
		if(theFilter=='showIncorrect'){
			$('#'+theFilter).addClass("filterBtnsHiLight");
		}else{
			$('#'+theFilter).addClass("filterBtns");	
		}
		if(theFilter=='showCorrect'){
			$('#'+theFilter).addClass("filterBtnsHiLight");
		}else{
			$('#'+theFilter).addClass("filterBtns");
		}
		if(theFilter=='showAll'){
			$('#'+theFilter).addClass("filterBtnsHiLight");
		}else{
			$('#'+theFilter).addClass("filterBtns");
		}
		if(theFilter=='showDoNotKnow'){
			$('#'+theFilter).addClass("filterBtnsHiLight");
		}else{
			$('#'+theFilter).addClass("filterBtns");
		}
		if(theFilter=='showConfIncorrect'){
			$('#'+theFilter).addClass("filterBtnsHiLightConfInc");
		}else{
			$('#'+theFilter).addClass("filterBtnsConfInc");
		}
	$('#detailsContainer').hide();
	$('#detailsContainer').slideDown('slow');
}
function detailsConfLvlCounter(aDetail){
	if(aDetail=="confident"){
		totalConfCount +=1;	
	} else if(aDetail=="uncertain"){
		totalUncertainCount+=1;
	} else {
		totalDoNotKnowCount+=1;
	}
}
function detailsAnsCorrectCounter(aDetail){
	if(aDetail == "confident"){
		totalConfCorrectCount+=1;	
	}	
	if(aDetail == "uncertain"){
		totalUncertainCorrectCount +=1;	
	}
	totalCorrect+=1;
}
function detailsAnsIncorrectCounter(aDetail){
	if(aDetail == "confident"){
		totalConfIncorrectCount+=1;	
	} else if(aDetail == "uncertain"){
		totalUncertIncorrectCount+=1;
	}
	totalIncorrect+=1;
}

//======== for flash ============
function getScoreForFlash(){
	return scoreForFlash;
}
function getPreScoreForFlash(){
	return scoreForFlashPre;	
}
function getConfidencePercent(){
	return confidencePercent.toString();	
}
function getPreConfScore(){
	preConfScoreForFlash = Math.round(preConfScoreForFlash);
	return preConfScoreForFlash;	
}
function showDetailsContainer(){
	$('#detailsContainer').slideDown('normal');
	$('#resultFilters').slideDown('fast');
	$('#detailsTitle').slideDown('fast');
	$('#contBtn').show();
	return 'complete';
}
function startTheClip(){
	return 'true';	
}
function getConfLvlDetails(){
	var theDataObj = new Object();
	theDataObj['totalAnswers'] = totalACount;
	theDataObj['totalConfident'] = totalConfCount;
	theDataObj['totalUncertain'] = totalUncertainCount;
	theDataObj['totalDoNotKnow'] = totalDoNotKnowCount;
	
	theDataObj['totalConfidentCorrect'] = totalConfCorrectCount;
	theDataObj['totalConfidentIncorrect'] = totalConfIncorrectCount;
	theDataObj['totalUncertainCorrect'] = totalUncertainCorrectCount;
	theDataObj['totalUncertainIncorrect'] = totalUncertIncorrectCount;
	
	theDataObj['totalCorrect'] = totalCorrect;
	theDataObj['totalIncorrect'] = totalIncorrect;
	return theDataObj;
} 
//===============================
var scoreForFlash='';
var scoreForFlashPre='';
var preConfScoreForFlash=0;
var confidencePercent=0;
var showAllCount=0;
var showCorrectCount=0;
var showDoNotKnowCount=0;
var showIncorrectCount=0;
var showConfIncorrectCount=0;
var resultObj;
var dataSourceAC = new Array();
var totalACount=0;
var totalConfCount=0;
var totalUncertainCount=0;
var totalDoNotKnowCount=0;
var totalConfCorrectCount=0;
var totalUncertainCorrectCount=0;
var totalConfIncorrectCount=0;
var totalUncertIncorrectCount=0;
var totalCorrect=0;
var totalIncorrect=0;

$('document').ready(init);
</script>
<style type="text/css">
body{
	font-family:Tahoma, Geneva, sans-serif;
	font-size:12px;	
	margin:0px;
	/*background-color:#626262;*/
	background-image:url(images/pageBG_vertSlice.jpg);
	background-repeat:repeat-x;
	background-attachment: fixed;
	background-color:#353535;
}
#pageWrapper{
	width:850px;
	margin:auto;
	border:solid 2px #CCC;
	background-color:#6f8c9b;
}
ul{
	list-style:square;
	margin-top:0px;
}
.ulAnsClss{
	
}
li{
	width:355px;
	padding-right:5px;
}
#userDisplay{
	color:#FC0;
	background-color:#000;
	padding:5px;
}
.postTScore{
	font-size:36px;
	color:#06C;	
}
.preTScore{
	/*float:left;*/	
}
#detailsBar{
	background-color:#333;
	color:#FC0;	
}
.qSpacer{
	height:30px;
	width:850px;
	background-color:#6f8c9b;	
}
#testAnswers{
	width:260px;
	float:left;	
	border: solid 1px #F00;
}
.tAnswers{
	margin-top:0px;
	margin-bottom:0px;
	padding-left:0px;
	color:#C1C1C1;	
	font-size:13px;
}
.testQClss{
	margin:0px;
	padding-bottom:4px;
	font-size:16px;
	font-weight:bold;
}
#userAnswers{
	width:200px;
	float:left;
	border: solid 1px #069;	
}
#mainWrapper{
	width:850px;	
}
#detailedStats{
	width:100%;
	border:solid 1px #093;	
}
.tQuestionContainer{
	/*
	width:100%;
	border: solid 1px #0C0;
	background-color:#FF0;
	*/
	font-size:22px;
	color:#4D4D4D;
	/*background-color:#036;*/
	background-image:url(images/testingGraphics2_resultQBG.jpg);
	background-repeat:no-repeat;
	background-position:bottom left;
	padding-left:8px;
	padding-top:4px;
	padding-bottom:4px;
	padding-right:6px;
	width:836px;
	/*border:solid 1px #F00;*/
}
/* QUESTION WRAPPER ==================== */
.tQContainerClss{
	background-color:#000;
}
/* ==================================== */
.tAnsContainer{
	float:left;
	width:402px;
	border-right:solid 1px #09F;
	border-bottom:solid 1px #09F;
	border-left:solid 1px #09F;
	background-image:url(images/ansBG.jpg);
	background-repeat:no-repeat;
	background-position:top left;
	padding-top:40px;
}
.usersAnswer{
	padding-right:6px;
	padding-left:38px;
	padding-top:12px;
	margin-top:0px;
	/*border:solid 2px red;*/
	color:#036;
	font-size:16px;
	font-weight:bold;
}
.userAnswerContainer{
	/*border:solid 2px red;*/	
}
.userAnswerContainer_confident{
	float:right;
	width:445px;
	min-height:75px;
	height:auto !important;
	height:75px;
	background-image:url(images/endUserAnswerBG_confident.jpg);
	background-repeat:no-repeat;
	background-position:top left;
	padding-top:36px;
	margin:0px;
}
.userAnswerContainer_unCertain{
	float:right;
	width:445px;
	min-height:75px;
	height:auto !important;
	height:75px;
	background-image:url(images/endUserAnswerBG_uncertain.jpg);
	background-repeat:no-repeat;
	background-position:top left;
	padding-top:36px;
	margin:0px;
}
.userAnswerContainer_doNotKnow{
	float:right;
	width:445px;
	min-height:75px;
	height:auto !important;
	height:75px;
	background-image:url(images/endUserAnswerBG_doNotKnow.jpg);
	background-repeat:no-repeat;
	background-position:top left;
	padding-top:36px;
	margin:0px;
}
.userScoreDisplay{
	float:right;
	padding-right:16px;
	margin-top:24px;
	font-size:42px;
}
.answerCorrect{
	background-image:url(images/ansCorrect.jpg);
	background-repeat:no-repeat;
	background-position:top right;
	width:445px;	
	float:right;
	height:105px;
	/*margin-right:1px;*/
	border-bottom:#999 solid 1px;
	font-size:14px;
}
.answerInorrect{
	background-image:url(images/ansIncorrect.jpg);
	background-repeat:no-repeat;
	background-position:top right;
	float:right;
	width:445px;
	height:105px;
	/*margin-right:1px;*/
	border-bottom:#999 solid 1px;
}
.answerNeutral{
	background-image:url(images/ansNeutral.jpg);
	background-repeat:no-repeat;
	background-position:top right;
	float:right;
	width:445px;
	height:105px;
	/*margin-right:1px;*/
	border-bottom:#999 solid 1px;	
}
.ansExplanation{
	float:left;
	padding-left:125px;
	padding-right:10px;
	padding-top:4px;
	padding-bottom:4px;
	border:solid 1px #666;
	background-image:url(images/explanationBG.jpg);
	background-repeat:no-repeat;
	background-position:top left;
	background-color:#000;
	/*color:#0F0; BRIGHT GREEN COLOR */
	color:#CDCDCD;
	font-size:12px;
	margin-top:5px;
	margin-bottom:5px;
	min-height:45px;
	height:auto !important;
	height:45px;
	width:713px;
}
#showIncorrect{
	width:150px;
	float:left;
}
#showCorrect{
	width:135px;
	float:left;
}
#showAll{
	width:100px;
	float:left;
}
#showDoNotKnow{
	width:172px;
	float:left;
}
#showConfIncorrect{
	width:265px;
	float:left;
}
.qCountContainer{
	background-image:url(images/testingGraphics2_resultQBGTop.jpg);
	background-repeat:no-repeat;
	height:36px;
	width:725px;
	padding-left:125px;
	padding-top:6px;
	font-weight:bold;
	font-size:22px;
	color:#F7E18C;
}
.qCountClss{
	color:#FCEFBC;
	margin:0px;
}
.filterBtns{
	color:#E2E2E2;
	text-align:center;
	padding-top:10px;
	height:26px;
	border-left:solid 1px #333;	
	border-right:solid 1px #333;
	background-image:url(images/filterBtns.jpg);
	background-repeat:no-repeat;
	background-position:center;
	cursor:pointer;
}
.filterBtnsConfInc{
	color:#E2E2E2;
	text-align:center;
	padding-top:10px;
	padding-left:8px;
	height:26px;
	border-left:solid 1px #333;	
	border-right:solid 1px #333;
	background-image:url(images/confIncorrectBG_01.jpg);
	background-repeat:no-repeat;
	background-position:left;
	cursor:pointer;
}
.filterBtnsHiLight{
	color:#E2E2E2;
	text-align:center;
	padding-top:10px;
	padding-left:8px;
	height:26px;
	border-left:solid 1px #333;	
	border-right:solid 1px #333;
	background-image:url(images/filterBtns_hiLight.jpg);
	background-repeat:no-repeat;
	background-position:center;
	cursor:pointer;
}
.filterBtnsHiLightConfInc{
	color:#E2E2E2;
	text-align:center;
	padding-top:10px;
	height:26px;
	border-left:solid 1px #333;	
	border-right:solid 1px #333;
	background-image:url(images/confIncorrectBG_01HiLite.jpg);
	background-repeat:no-repeat;
	background-position:left;
	cursor:pointer;
}
.ansStatsClss{
	float:left;
	height:92px;
	width:405px;
	color:#FFF;
	background-image:url(images/ansStatsBG.jpg);
	background-repeat:no-repeat;
}
.ansStatsTxtClss{
	margin:0px;
	margin-top:32px;
	margin-left:45px;
	color:#9F0;
}
#detailsTitle{
	background-image:url(images/testResultDetailsText_txt2.jpg);
	background-repeat:no-repeat;	
	width:520px;
	height:112px;
	margin-left:175px;
	margin-top:10px;
	margin-bottom:10px;
}
#resultFilters{
	border:solid 1px #000;
	background-color:#000;
	margin-bottom:25px;	
}
#edTitle{
	color:#CCC;
	background-color:#000;	
	padding-left:5px;
	padding-top:2px;
	padding-bottom:2px;
	border-bottom:solid 1px #666;
}
#bgWrapper{
	width:1000px;
	height:100%;
	margin:auto;
	/*border:solid 2px red;*/
	background-attachment: fixed;
	background-image:url(images/pageBG.jpg);
	background-repeat: no-repeat;
	background-position: center top;
}
</style>
</head>

<body>
<div id="bgWrapper">
<div id="pageWrapper">
<div id="mainWrapper">
<div id="userDisplay">
    	Welcome <?php echo $endUserObj->f_name . ' ' . $endUserObj->l_name . '!'; ?>
    </div>
    <div id="edTitle">Test Title: <?php echo $edTitle; ?></div>
<div id="flashDisplay">
  <object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="850" height="305">
    <param name="movie" value="testFeedback.swf" />
    <param name="quality" value="high" />
    <param name="wmode" value="opaque" />
    <param name="swfversion" value="6.0.65.0" />
    <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
    <param name="expressinstall" value="../Scripts/expressInstall.swf" />
    <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
    <!--[if !IE]>-->
    <object type="application/x-shockwave-flash" data="testFeedback.swf" width="850" height="305">
      <!--<![endif]-->
      <param name="quality" value="high" />
      <param name="wmode" value="opaque" />
      <param name="swfversion" value="6.0.65.0" />
      <param name="expressinstall" value="../Scripts/expressInstall.swf" />
      <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
      <div>
        <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
        <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
      </div>
      <!--[if !IE]>-->
    </object>
    <!--<![endif]-->
  </object>
</div>

    <!--<div id="scoreContainer"></div>-->
    <div style="clear:both"></div>
    <!--<div id="detailsBar">Details</div>-->
    <div id="detailsContainerTop">
    	<div id="resultFilters">
    		<div id="showAll" class="filterBtnsHiLight">Show All</div> 
    		<div id="showCorrect" class="filterBtns">Show Correct</div> 
    		<div id="showIncorrect" class="filterBtns">Show Incorrect</div> 
    		<div id="showConfIncorrect" class="filterBtnsConfInc">Show Confidently Incorrect</div>
    		<div id="showDoNotKnow" class="filterBtns">Show All "Do Not Know"</div> 
    		<div style="clear:both"></div>
    	</div>
        <div id="detailsTitle"></div>
    </div>
    <div id="detailsContainer"></div>
    <div id="endTestControlsContainer">
    	<img id="contBtn" style="float:right;cursor:pointer" src="images/testBtns_continue.jpg" width="226" height="89"/>
    <div style="clear:both"></div>    
    </div>
    

</div>
</div>
</div>
<?php //echo $_SESSION['totalScore']; ?>
<script type="text/javascript">
swfobject.registerObject("FlashID");
</script>
</body>
</html>