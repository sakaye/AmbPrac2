<?php session_start();
if(isset($_SESSION['end_user_id'])){
	$userID = $_SESSION['end_user_id'];
}
// ========== set the education id variable to use to submit completions to db = .php?eductionID=COURSE_ID from index
$edID = 'null';
if(isset($_SESSION['edID'])){
	$edID = $_SESSION['edID'];
}
//============================================================================
// =========== GRAB SESSION VARS =============================
$userID = $_SESSION['end_user_id'];
$prePost = $_SESSION['pre_post'];

// =====================================
require('getEndUserData.php');
// =============== ID Test Type ================
if($prePost == 'pre'){
	$theTestType = '"pre"';	
} else if($prePost == 'post'){
	$theTestType = '"post"';	
} else{
	$theTestType = "";	
}
// get end-user information ======
define('DB_CONN','dbConnect.php'); 
require_once DB_CONN;
require 'EndUserVO.php';
$endUserObj = getUserNFO($userID);
$_SESSION['usersNameForResultsFName'] = $endUserObj->f_name;
$_SESSION['usersNameForResultsLName'] = $endUserObj->l_name;
$_SESSION['usersNameForResultsMName'] = $endUserObj->m_init;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AmbulatoryPractice.org - Confidence-Based Testing Tool</title>
<script type="text/javascript" src="jquery.min.js"></script>
<script src="swfobject_modified.js" type="text/javascript"></script>
<script type="text/javascript">
function init(){
	$('#flaContainer').hide();
	$.post('edCheck.php',{'noVal':'true'},edCheckHandler,"json");
	$('#loadingTxt').hide();
	$('#subAllAnsTxt').hide();
	$('#preLoadImages').hide();
	$('#testInstructions').click(function(){
		window.open('cbt_instructions.php','myWin',"width=700, height=280");
	})
}
function edCheckHandler(result){
	if(result[0] == 'true'){
		
		// go to del page
		var edExistsResponse = '<div id="retakeTestDiv"><p>It appears you have already submitted a <?php echo $theTestType; ?> test for this topic.<br />You scored <span class="prevScoreClss">' + result[2] + '%</span>. Would you like to retake this test?</p>' +
		'<div id="btnContainer"><div id="retakeTest" style="cursor:pointer"></div><div id="continueTest" style="cursor:pointer"></div></div>' +
		'<div style="clear:both"></div>' +
		'<div id="retakeTestInstructions">* Note: re-taking the test will result in the deletion of your previous test scores.</div>' +
		'</div>';
		$(edExistsResponse).appendTo('#mainContent');
		$('#retakeTest').click(function(){
			retakeTest();
		})
		
		$('[id=retakeTest]').addClass("retakeTestTxt");
		$('[id=continueTest]').addClass("continueTestClss");
		$('[id=retakeTestInstructions]').hide();
		
		$('[id=retakeTest]').mouseover(function(){
			$(this).addClass("retakeHiLight");
			$('[id=retakeTestInstructions]').show();
		})
		$('[id=retakeTest]').mouseout(function(){
			$(this).removeClass("retakeHiLight");
			$('[id=retakeTestInstructions]').hide();
		})
		
		$('[id=continueTest]').mouseover(function(){
			$(this).addClass("continueTestHiLightClss");
		})
		$('[id=continueTest]').mouseout(function(){
			$(this).removeClass("continueTestHiLightClss");
		})
		
		$('#continueTest').click(function(){
			window.location.href="cbtResponse.php";
		})
		//window.location.href="ed_del_record.php";
	} else {
		proceedToTest();
	}
	var tempEdTitle = result[1];
	$('#testTitle').text('Test Title: ' + tempEdTitle);
	if(result[3]=='sa'){
		$('#continueTest').hide();
		$('#retakeTest').css("float","none");
		$('#retakeTest').css("margin-left","225px");
	}
	if(result[4]=="sendToPreTest"){
		alert("No Pretest Exists!!!!");	
	}
}
// ==========================================================================
function proceedToTest(){
	$('#subAllAnsTxt').show();
	$.getJSON('QAGrabber.php',handler);

	
}
function retakeTest(){
	// REMOVE ALL PRETEST DETAILS 
	delAllData = 'true';
	$('#retakeTestDiv').remove();
	//$.post('ed_del_record.php',{'noVal':'true'},removeTestDetailsComplete,"json");
	proceedToTest();
}
function removeTestDetailsComplete(result){
	//proceedToTest();	
}
function handler(result){
	dataSourceAC = result;
	
	createMainQAWrappers();
	populateInitialQAData();
}
function createMainQAWrappers(){
	var tmpCounter=0;
	var vertSpacerDiv = '<div id="topSpacerDiv" style="height:20px;"></div>';
	
	$(vertSpacerDiv).appendTo("#mainContent");
	
	$(dataSourceAC).each(function(){
		
		var tmpQAWrapper = '<div id="qaWrapper_' + tmpCounter + '" acIndex=' + tmpCounter + ' class="qaWrapperClss" active="false"></div>';
		
		$(tmpQAWrapper).appendTo('#mainContent');
		
		var questionCounterBar = '<div id="questionCountBar_' + tmpCounter + '" acIndex=' + tmpCounter + ' class="qCounterBar">' + Number(tmpCounter+1) + ' of ' + dataSourceAC[0].qCount + '</div>';
		console.log("sanity check 3");
		$(questionCounterBar).appendTo('#qaWrapper_' + tmpCounter);
		
		var tmpMicroObj = '<div id="microContainer_' + tmpCounter + '" acIndex="' + tmpCounter + '" class="microWrapperClss">' + 
						  '<div id="microTop_' + tmpCounter + '"></div> ' +
						  '<div id="microMid_' + tmpCounter + '" class="microMidContainerClss">' +
						  
						  		'<div id="microMainMid_' + tmpCounter + '" class="microMainMidClss">' +
						  			'<p id="microQuestion_' + tmpCounter + '" class="microQClss"></p>' +
									'<p id="microUserConfLvl_' + tmpCounter + '"></p>' +
									'<p id="microUserAnswer_' + tmpCounter + '" class="feedbackAnsClssMicro"></p>' +
								'</div>' +
								
								'<div id="microAnsCheck_' + tmpCounter + '" class="microAnsCheckClss">' +
								'</div>' +
								
						  		'<div style="clear:both"></div>' +
						  '<div>' +
						  '<div id="microBot_' + tmpCounter + '"><div>' + 
						  '</div>';
		$(tmpMicroObj).appendTo('#qaWrapper_' + tmpCounter);
		
		var tmpQAObj = '<div id="qaContainer_' + tmpCounter + '" acIndex=' + tmpCounter + ' alreadyShown="true" class="qaContainerClss">' +
							'<div id="qCount_' + tmpCounter + '" ' +
								   'class="defaultQTopClss_HOLD">' +
							'</div>' +
							'<div id="question_' + tmpCounter +'" ' +
								   'qID="' + dataSourceAC[tmpCounter].qID + '" ' +
								   'ptsPoss="' + dataSourceAC[tmpCounter].ptsPoss + '" ' +
								   'class="defaultQClss"><p style="font-size:2px">_</p>' +
								   '<div id="qArrow_' + tmpCounter + '" class="qArrowClss"></div>' +
								   '<div id="qTxtContainer_' + tmpCounter + '" class="qTxtContainerClss"></div>' +
								   '<div id="ansGroupFeedback_' + tmpCounter + '" class="ansGroupFeedbackClss"></div>' +
								   '<div id="feebackAns_' + tmpCounter + '" class="feedbackAnsClss"></div>' +
								   '<div style="clear:both"></div>' +
							'</div>' +
							'<div id="qDefaultViewBottom_' + tmpCounter + '" ' +
								   'class="defaultQBottomClss">' +
							'</div>' +
							'<div id="answerContainer_' + tmpCounter + '" class="answerContainer">' +
								'<div id="ansGroupA_' + tmpCounter + '" ' +
									  'ansGroup="setA" ' +
									  'class="ansGroupAClss">' +
									  		'<table id="ansGroupATbl_' + tmpCounter + '" class="ansTbl"></table>' +
								'</div>' +
								
								'<div id="ansGroupB_' + tmpCounter + '" ' +
									  'ansGroup="setB" ' + 
									  'class="ansGroupBClss">' +
									  		'<table id="ansGroupBTbl_' + tmpCounter + '" class="ansTbl"></table>' +
								'</div>' + 
								
								'<div id="ansGroupC_' + tmpCounter + '" ' +
										'ansGroup="setC"' + 
										'class="ansGroupCClss">' +
											'<table id="ansGroupCTbl_' + tmpCounter + '" class="ansTbl"></table>' +
										'<div id="ansCheck_' + tmpCounter + '" class="ansCheckclss"></div>' +
								'</div>' +
								'<div id="clearWTF_' + tmpCounter + '" style="clear:both; color:#141414; width:100%; display:block; padding-left:5px;">| &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; |</div>' +
								'<div id="ansBottom_' + tmpCounter + '" class="ansBottomClss"></div>' +
							'</div>' +
					   '</div>';
		$(tmpQAObj).appendTo('#qaWrapper_' + tmpCounter);
		
		var bottomNavObj = '<div id="qBottom_' + tmpCounter + '" class="qBottomClss">' +
							'<div id="nextBtn_' + tmpCounter + '" ' +
								  'acIndex="' + tmpCounter +'" class="nextQBtnClss">' +
								  '<p id="nextBtnTxt_' + tmpCounter + '" class="nextBtnTxtClss">Minimize</p>' +
								  '</div>' +
								  '<div style="clear:both"></div>' +
							'</div>';
		$(bottomNavObj).appendTo('#qaWrapper_' + tmpCounter);
		tmpCounter+=1;
	});
	
}
function populateInitialQAData(){
	var counter=0;
	$(dataSourceAC).each(function(){
		$('#microQuestion_'+ counter).text(dataSourceAC[counter].question);
		$('#qTxtContainer_' + counter).text(dataSourceAC[counter].question);
		var ansCounter = 0;
		var answersLength = dataSourceAC[counter].ansObj.length;
		var answersLengthHalf = answersLength/2;
		
		var setBCounter=0;
		var correctAnsSetA='false';
		var correctAnsSetB='false';
		var ansGroupBSetA='';
		var ansGroupBSetAHTML='';
		var ansGroupBSetB='';
		var ansGroupBSetBHTML='';
		var tempAnsID =0;
		$(dataSourceAC[counter].ansObj).each(function(){
			// ================================ create group A = confident group =========================
			var ansSetA = '<tr acIndex="' + counter + '">' +
			  					'<td width="15px" valign="top">' +
								'<input type="radio" name="rbSet_' + counter + '" ' +
								'correctAns="' + dataSourceAC[counter].ansObj[ansCounter].correct + '" ' +
								'acIndex="'+ counter +'" ' +
								'ansID="'+ dataSourceAC[counter].ansObj[ansCounter].ansID +'" ' +
								'ansTxt="'+ dataSourceAC[counter].ansObj[ansCounter].ans +'" ' +
								'ansSet="setA"/>' +
								'</td>' +
							   	'<td><p>' + dataSourceAC[counter].ansObj[ansCounter].ans + '</p></td>' +
						  '</tr>';
			$(ansSetA).appendTo('#ansGroupATbl_' + counter);
			
			// ============================ break all answers in half for group B = uncertain group ===============
			if(setBCounter<answersLengthHalf){
				if(dataSourceAC[counter].ansObj[ansCounter].correct=='true'){
					correctAnsSetA='true';
				}
				ansGroupBSetA += dataSourceAC[counter].ansObj[ansCounter].ans + '; ';
				ansGroupBSetAHTML += '<p>' + dataSourceAC[counter].ansObj[ansCounter].ans + ',</p>';
				tempAnsID = dataSourceAC[counter].ansObj[ansCounter].ansID;
			} else {
				if(dataSourceAC[counter].ansObj[ansCounter].correct=='true'){
					correctAnsSetB='true';
				}
			    ansGroupBSetB += dataSourceAC[counter].ansObj[ansCounter].ans + '; ';
				ansGroupBSetBHTML += '<p>' + dataSourceAC[counter].ansObj[ansCounter].ans + ',</p>';
				tempAnsID = dataSourceAC[counter].ansObj[ansCounter].ansID;
			}
			
			setBCounter+=1;
			ansCounter+=1;
		})
		
		// ================================ create group B = uncertain group ==============================
		var ansGroupBSetATR = '<tr acIndex="' + counter + '">' +
								'<td width="15px" valign="top">' +
								'<input type="radio" name="rbSet_' + counter + '" ' +
								'correctAns="'+ correctAnsSetA +'" ' +
								'acIndex="'+ counter +'" ' +
								'ansID="' + tempAnsID  + '" ' +
								'ansTxt="'+ ansGroupBSetA +'" ' +
								'ansSet="setB"/>' +
								'</td>' +
								'<td><p style="font-weight:bold">the answer is either:</p>'+ ansGroupBSetAHTML + '</td>' +
								'</tr>';
								
		var ansGroupBSetBTR = '<tr acIndex="' + counter + '">' +
								'<td width="15px" valign="top">' +
								'<input type="radio" name="rbSet_' + counter + '" ' +
								'correctAns="'+ correctAnsSetB +'" ' +
								'acIndex="'+ counter +'" ' +
								'ansID="' + tempAnsID + '" ' +
								'ansTxt="'+ ansGroupBSetB +'" ' +
								'ansSet="setB"/>' +
								'</td>' +
								'<td><p style="font-weight:bold">OR either:</p>'+ ansGroupBSetBHTML + '</td>' +
								'</tr>';
								
		$(ansGroupBSetATR).appendTo('#ansGroupBTbl_'+counter);
		$(ansGroupBSetBTR).appendTo('#ansGroupBTbl_'+counter);
		
		// =========================== create group C = dont know the answer group ========================
		var ansGroupC = '<tr acIndex="' + counter + '">' +
							'<td width="15px" valign="top">' +
							'<input type="radio" name="rbSet_' + counter + '" ' +
							'correctAns="noAns" ' +
							'acIndex="'+ counter +'" ' +
							'ansID="' + tempAnsID + '" ' +
							'ansTxt="I don\'t know the answer" ' +
							'ansSet="setC"/>' +
							'</td>' +
							'<td><p>I don\'t know the answer</p></td>' +
							'</tr>';
							
		$(ansGroupC).appendTo('#ansGroupCTbl_'+counter);
		
		counter+=1;
	})
	createSubmitButton();
	//$('tr:even').addClass("tr1");
	setUpRBEvents();
	setupEvents();
	postCreationSetup();
}
function createSubmitButton(){
	var tempBtn ='<div style="clear:both"></div><div id="subBtn" class="subBtnClss"></div><div style="clear:both"></div>';
	$(tempBtn).appendTo('#subBtnContainer');
	$('#subBtn').mousedown(function(){
		$(this).hide();
		$('#loadingTxt').show();
		checkDelData();
	});
	$('#subBtn').mouseover(function(){
		$(this).addClass("subBtnHiLightClss");
	});
	$('#subBtn').mouseout(function(){
		$(this).removeClass("subBtnHiLightClss");
	});
}
function setUpRBEvents(){
	$('input[type="radio"]').click(function(){
		var tempID = $(this).attr("acIndex");
		currentIndex = tempID;
		var userAnsTxt = $(this).attr("ansTxt");
		$('#microUserAnswer_'+tempID).text(userAnsTxt);
		$('#feebackAns_' + tempID).text(userAnsTxt);
		var confLvl = confidenceCheck(tempID,$(this).attr("ansSet"));
		//$('#microUserConfLvl_'+tempID).text(confLvl);
		checkAnswerCount();
		//alert($('#question_'+tempID).attr("ptsPoss"));
		$('#qaWrapper_'+tempID + ' tr').removeClass("showSelected");
		$(this).parents('tr').addClass("showSelected");
		$('#ansCheck_'+tempID).show();
		$('#microAnsCheck_'+tempID).show();
		showNxtBtn(tempID);
	})
}
function checkAnswerCount(){
	// ============ show submit button once all answers are checked =============
	if(dataSourceAC[0].qCount==$('input[type="radio"]:checked').length){
		$('#subBtnContainer').show();
		$('#subAllAnsTxt').hide();
	}	
}
function setupEvents(){
	$('[id^="nextBtn_"]').mousedown(function(){
		var tempIndex = $(this).attr("acIndex");
		//$('[id=qaContainer_' + tempIndex + ']').hide();
		if($('[id=qaContainer_' + tempIndex + ']').not(":hidden")){
			$('[id=qaContainer_' + tempIndex + ']').slideUp('normal',function(){
				$('#nextBtnTxt_'+tempIndex).text("Change Answer");
				$('#microContainer_' + tempIndex).slideDown('normal');
				$('#questionCountBar_'+tempIndex).addClass("qCounterBarMicro");
				var nextBtn = Number(tempIndex)+1;
				if($('[id=qaContainer_' + nextBtn + ']').attr('alreadyShown')=='false'){
					$('[id=qaContainer_' + nextBtn + ']').slideDown('normal');
					$('#questionCountBar_'+nextBtn).removeClass("qCounterBarMicro");
					$('[id=qaContainer_' + nextBtn + ']').attr('alreadyShown','true');
					$('[id=microContainer_' + nextBtn + ']').slideUp('fast');
				}
			});
		}
		if($('[id=qaContainer_' + tempIndex + ']').is(":hidden")){
			$('#microContainer_' + tempIndex).slideUp('normal',function(){
				$('#nextBtnTxt_'+tempIndex).text("Minimize");
				$('#questionCountBar_'+tempIndex).removeClass("qCounterBarMicro");
				$('[id=qaContainer_' + tempIndex + ']').slideDown('normal');
			});	
		}

	})
	$('[id^="nextBtn_"]').mouseover(function(){
		$(this).addClass("nextQBtnClssHiLight");
	})
	$('[id^="nextBtn_"]').mouseout(function(){
		$(this).removeClass("nextQBtnClssHiLight");
	})
	$('tr').mouseover(function(){
		$(this).addClass("showBorder");
	})
	$('tr').mouseout(function(){
		$(this).removeClass("showBorder");
	})
	// ======================================= ADD CLICK TD TO CHECK RB =====================
	$('tr').mousedown(function(){
		var tempIndex = $(this).attr("acIndex");
		$(this).children('td').children(':radio').attr('checked','checked');
		var userAnsTxt = $(this).children('td').children(':radio').attr("ansTxt");
		$('#microUserAnswer_'+tempIndex).text(userAnsTxt);
		$('#feebackAns_' + tempIndex).text(userAnsTxt);
		var confLvl = $(this).children('td').children(':radio').attr("ansSet");
		confidenceCheck(tempIndex,confLvl);
		//$('#microUserConfLvl_'+tempIndex).text(confLvl);
		$('#qaWrapper_'+tempIndex + ' tr').removeClass("showSelected");
		$(this).addClass("showSelected");
		$('#ansCheck_'+tempIndex).show();
		$('#microAnsCheck_'+tempIndex).show();
		showNxtBtn(tempIndex);
		checkAnswerCount();
	})
		
		// =======================================================================================
	/*
	$('[id^=qaWrapper_]').mouseover(function(){
		var tempIndex = $(this).attr("acIndex");
		if($('[id=qaContainer_' + tempIndex + ']').not(":hidden")){
			$('[id=qaContainer_' + tempIndex + ']').slideUp('normal',function(){
				$('#microContainer_' + tempIndex).slideDown('normal');
				$('#questionCountBar_'+tempIndex).addClass("qCounterBarMicro");
				/*var nextBtn = Number(tempIndex)+1;
				if($('[id=qaContainer_' + nextBtn + ']').attr('alreadyShown')=='false'){
					$('[id=qaContainer_' + nextBtn + ']').slideDown('normal');
					$('#questionCountBar_'+nextBtn).removeClass("qCounterBarMicro");
					$('[id=qaContainer_' + nextBtn + ']').attr('alreadyShown','true');
					$('[id=microContainer_' + nextBtn + ']').slideUp('fast');
				}
			});
		}
		if($('[id=qaContainer_' + tempIndex + ']').is(":hidden")){
			$('#microContainer_' + tempIndex).slideUp('normal',function(){
				$('#questionCountBar_'+tempIndex).removeClass("qCounterBarMicro");
				$('[id=qaContainer_' + tempIndex + ']').slideDown('normal');
			});	
		}
	})
	*/
	
}
function showNxtBtn(theID){
	$('#nextBtn_'+theID).slideDown('slow');
}
function confidenceCheck(theID,confLvl){
	$('#ansGroupFeedback_'+theID).removeClass();
	$('#microUserConfLvl_'+theID).removeClass();
	if(confLvl=='setA'){
		$('#microUserConfLvl_'+theID).addClass("ansGroupFeedbackClssConf");
		$('#microUserConfLvl_'+theID).text("Confident");
		$('#ansGroupFeedback_'+theID).addClass("ansGroupFeedbackClssConf");
		$('#ansGroupFeedback_'+theID).text("Confident");
	} else if(confLvl=='setB'){
		$('#ansGroupFeedback_'+theID).addClass("ansGroupFeedbackClssUncertain");
		$('#microUserConfLvl_'+theID).addClass("ansGroupFeedbackClssUncertain");
		$('#microUserConfLvl_'+theID).text("Uncertain");
		$('#ansGroupFeedback_'+theID).text("Uncertain");
	} else {
		$('#ansGroupFeedback_'+theID).addClass("ansGroupFeedbackClssDoNotKnow");
		$('#microUserConfLvl_'+theID).addClass("ansGroupFeedbackClssDoNotKnow");
		$('#microUserConfLvl_'+theID).text("Do Not Know");
		$('#ansGroupFeedback_'+theID).text("Do Not Know");
	}
}
function setStyles(){

}
function postCreationSetup(){
	$('#subBtnContainer').hide();
	$('[id^="microContainer_"]').hide();
	/*$('[id="microContainer_0"]').hide();*/
	/*
	var indexCounter=0;
	$('[id^=qaContainer_]').each(function(){
		if(indexCounter>0){
			$(this).hide();
			var tmpID = $(this).attr("acIndex");
			$('#questionCountBar_'+tmpID).addClass("qCounterBarMicro");
		}
		indexCounter+=1;
	})
	*/
	$('[id^=ansCheck_]').hide();
	$('[id^=microAnsCheck_]').hide();
	$('[id^="nextBtn_"]').hide();
}
// ================ set up events =========================
function checkDelData(){
	if(delAllData=='true'){
		$.post('ed_del_record.php',{'noVal':'true'},grabAllData,"json");
	} else {
		grabAllData();
	}
}

// ================================== SEND ALL DATA TO PHP ========================= //
function grabAllData(){
		// ======= CREATE ARRAYS OF DATA TO SEND ===========
		var ansIDArry = new Array();
		//var ansScoreArry = new Array();
		var qIDArry = new Array();
		var ansGroupArry = new Array();
		var ansCorrectArry = new Array();
		var ptsPossArry = new Array();
		var setBAnswersArry = new Array();
		var counter = 0;
		// ================ LOOP THROUGH ALL TEST DATA =========================== //
	$(':radio:checked').each(function(){
		//var tempData = $(this).prev().val() + ', ' + $(this).next().val() + ', ' + $(this).parents("div[id^=ansContainer]").attr('value') + ', qID= ' + $(this).parents('div[id^=qContainerTag]').attr('value') + ' ptsPoss= ' + $(this).parents('div[id^=qContainerTag]').next().attr('value');
        // LOOP THROUGH 
		//ansIDArry[counter] = $(this).prev().val();// answer ID
		var tempIndex = 0;
		tempIndex = $(this).attr("acIndex");
		ansIDArry[counter] = $(this).attr("ansID");
		//ptsPossArry[counter] = $(this).parents('div[id^=qContainerTag]').next().attr('value');
		ptsPossArry[counter] = $('#question_'+tempIndex).attr("ptsPoss");
		//qIDArry[counter] = $(this).parents('div[id^=qContainerTag]').attr('value');
		qIDArry[counter] = $('#question_'+tempIndex).attr("qID");
		//ansGroupArry[counter] = $(this).parents("div[id^=ansContainer]").attr('value');
		ansGroupArry[counter] = $(this).attr("ansSet");
		//ansCorrectArry[counter] = $(this).next().val();
		ansCorrectArry[counter] = $(this).attr("correctAns");
		//setBAnswersArry[counter] = $(this).siblings('[class=setBAnsString]').val();
		setBAnswersArry[counter] = $(this).attr("ansTxt");
		//alert(tempData);
		counter +=1;
	})
	// ================= CONVERT ARRAYS TO STRINGS =========================
	var tempDataObj = new Object();
	tempDataObj['ansID'] = ansIDArry.toString();
	tempDataObj['ptsPoss'] = ptsPossArry.toString();
	tempDataObj['qID'] = qIDArry.toString();
	tempDataObj['ansGroup'] = ansGroupArry.toString();
	tempDataObj['ansCorrect'] = ansCorrectArry.toString();
	tempDataObj['setBAns'] = setBAnswersArry.toString();

	tempDataObj['edID'] = <?php echo $edID; ?>;// = integer, no quotes
	tempDataObj['userID'] = '<?php echo $userID; ?>';
	tempDataObj['pre_post'] = '<?php echo $prePost; ?>';
	// ========== POST TO PHP =========================
	$.post('testProcessor.php',tempDataObj,testHandler,"json");
}
function testHandler(result){
	if((result[0]=='post')||(result[0]=='sa')){
		window.location.href="endUserResults.php";
	} else {
		window.location.href="cbtResponse.php";
	}
	
}
var dataSourceAC = new Array();
var currentIndex=0;
var delAllData='false';
// ============================== FOR FLASH ==========================
function startTheClip(){
	return 'false';	
}
//====================================================================

// ===================== INITIALIZE DOC =============================== //
$('document').ready(init);
</script>
<style type="text/css">
#mainContent{
 	background-color:#6f8c9b;	
}
body{
	font-family:Tahoma, Geneva, sans-serif;
	font-size:12px;	
	margin:0px;
	background-image:url(images/pageBG_defaultVertSlice.jpg);
	background-repeat:repeat-x;
	background-attachment: fixed;
	background-color:#353535;
}
p {
	margin:0px;	
}
td{
	border-bottom: solid 1px #666;
	cursor:pointer;
}
#pageWrapper{
	width:856px;
	margin:auto;
	border:solid 2px #CCC;
	background-color:#FFF;
}
#userDisplay{
	color:#FC0;
	background-color:#000;
	padding:5px;
}
.qCounterBar{
	background-image:url(images/testingGraphics_qTop.jpg);
	background-repeat:no-repeat;
	width:730px;
	/*border:solid 2px red;*/
	height:34px;
	padding-top:10px;
	padding-left:122px;
	color:#FADFA3;
	font-weight:bold;
	font-size:20px;
}
.qCounterBarMicro{
	background-image:url(images/testingGraphics_microQTop.jpg);
	background-repeat:no-repeat;
	width:730px;
	height:34px;
	padding-top:10px;
	padding-left:122px;
	color:#FADFA3;
	font-weight:bold;
	font-size:20px;
}
/* ==== WRAPS EACH ENTIRE QUESTION == */
.qaWrapperClss{
	width:855px;
	margin-bottom:20px;
	/*border:solid 2px red;*/
}
.defaultQTopClss{
	background-image:url(images/testingGraphics_qTop.jpg);
	background-repeat:no-repeat;
	width:100%;
	height:44px;
	padding-top:0px;
	padding-bottom:20px;
	padding-left:0px;
	padding-right:5px;
}
.defaultQClss{
	background-image:url(images/testingGraphics_qMid.jpg);
	background-repeat:repeat-y;
	font-size:16px;
	font-weight:bold;
	padding-left:10px;
	padding-right:10px;
	padding-top:0px;
	/*border:solid 2px red;*/
}
.defaultQBottomClss{
	background-image:url(images/testingGraphics_qBottom.jpg);
	background-repeat:no-repeat;
	background-position:bottom left;
	width:100%;
	height:5px;
}
.microWrapperClss{
	width:100%;
	background-image:url(images/testingGraphics_microQMid.jpg);
	background-repeat:no-repeat;
}
.qaContainerClss{
	width:100%;
	/*border:solid 1px blue;*/	
}
.microQClss{
	font-size:14px;
	color:#666;
	/*font-weight:bold;*/
	padding-left:5px;
	padding-bottom:4px;
}
.microMidContainerClss{
	background-color:#F8F8F8;
	background-image:url(images/testingGraphics_microQMid.jpg);
	background-repeat:repeat-y;
	padding-left:15px;
	padding-top:4px;
	padding-right:10px;
	width:827px;
}
.answerContainer{
	width:855px;
	background-image:url(images/testingGraphics_ansTop.jpg);
	background-repeat:no-repeat;
	padding-top:48px;
	color:#CCC;
	font-size:13px;
}
.ansGroupAClss{
	width:300px;
	/*border:solid 1px green;*/
	float:left;	
	min-height:125px;
}
.ansGroupBClss{
	width:300px;
	/*border:solid 1px green;*/
	float:left;
	min-height:125px;
}
.ansGroupCClss{
	width:235px;
	/*border:solid 1px green;*/
	float:left;
	min-height:125px;
}
.answerContainer{
	/*background-color:orange;*/	
}
.tr1{
	background-color:#E4E4E4;
}
.qBottomClss{
	background-image:url(images/testingGraphics_minMax.jpg);
	background-repeat:no-repeat;
	height:72px;
	color:#333;
}
.showBorder{
	background-color:#FC0;
	color:#333;
	font-weight:bold;
}
#subBtnContainer{
	border-top:solid 4px #E4E4E4;	
}
.subBtnClss{
	float:right;
	width:250px;
	height:100px;
	background-image:url(images/subAnsBtn.jpg);
	background-repeat:no-repeat;
	cursor:pointer;
}
.subBtnHiLightClss{
	float:right;
	width:250px;
	height:100px;
	background-image:url(images/subAnsBtn_hiLight.jpg);
	background-repeat:no-repeat;
	cursor:pointer;
}
#subAllAnsTxt{
	font-size:24px;
	color:#666;
	padding-top:10px;
	padding-bottom:35px;
	text-align:center;
	border-top:solid 4px #E4E4E4;
}
.ansTbl{
	width:100%;	
}
.showSelected{
	background-color:#87CDF8;
	color:#333;
}
.ansBottomClss{
	background-image:url(images/testingGraphics_ansBottom.jpg);
	background-repeat:no-repeat;
	height:45px;
	width:855px;
}
.qTxtContainerClss{
	float:right;
	margin-top:5px;
	padding-bottom:6px;
	width:790px;
	vertical-align:middle;	
	/*border:solid 2px red;*/
}
.qArrowClss{
	float:left;
	background-image:url(images/testingGraphics_qArrow.jpg);
	background-repeat:no-repeat;
	width:40px;
	height:52px;	
}
.ansCheckclss{
	background-image:url(images/testingGraphics_ansCheck.jpg);
	background-repeat:no-repeat;
	margin-top:40px;
	margin-left:10px;
	width:233px;
	height:69px;
}
.microMainMidClss{
	float:left;
	width:715px;	
}
.microAnsCheckClss{
	float:right;
	width:100px;
	height:68px;
	width:80px;
	background-image:url(images/testingGraphics_microCheck.jpg);
	background-repeat:no-repeat;
	background-position:top;
}
.nextQBtnClss{
	float:left;
	background-image:url(images/testingGraphics_nxtBtn.jpg);
	background-repeat:no-repeat;
	margin-top:4px;
	margin-right:6px;
	width:854px;
	height:37px;
	/*padding-right:200px;*/
	padding-top:5px;
	font-size:18px;
	font-weight:bold;
	cursor:pointer;
	text-align:right;
}
.nextQBtnClssHiLight{
	float:left;
	background-image:url(images/testingGraphics_nxtBtnHiLight.jpg);
	background-repeat:no-repeat;
	margin-top:4px;
	margin-right:6px;
	width:854px;
	height:37px;
	/*padding-right:200px;*/
	padding-top:5px;
	font-size:18px;
	font-weight:bold;
	cursor:pointer;
	text-align:right;
}
.feedbackAnsClss{
	width:480px;
	float:left;
	/*border:solid 1px blue;*/
	padding-top:6px;
	padding-left:10px;
	font-size:15px;
	font-weight:bold;
	color:#069;

}
.feedbackAnsClssMicro{
	width:380px;
	float:left;
	/*border:solid 1px blue;*/
	padding-top:6px;
	padding-left:10px;
	font-size:15px;
	font-weight:bold;
	color:#069;

}
.ansGroupFeedbackClss{
	width:105px;
	float:left;
	height:35px;
}
.ansGroupFeedbackClssConf{
	background-image:url(images/testingGraphics_answeredConf.jpg);
	background-repeat:no-repeat;
	width:105px;
	height:28px;
	float:left;
	padding-left:180px;
	padding-top:10px;
	font-size:14px;
	font-weight:bold;
	color:#FFF;
	/*border:solid 1px red;*/
}
.ansGroupFeedbackClssUncertain{
	background-image:url(images/testingGraphics_answeredUncertain.jpg);
	background-repeat:no-repeat;
	width:105px;
	height:28px;
	float:left;
	padding-left:180px;
	padding-top:10px;
	font-size:14px;
	font-weight:bold;
	color:#333;
	/*border:solid 1px red;*/
}
.ansGroupFeedbackClssDoNotKnow{
	background-image:url(images/testingGraphics_answeredDoNotKnow.jpg);
	background-repeat:no-repeat;
	width:105px;
	height:28px;
	float:left;	
	padding-left:170px;
	padding-top:10px;
	font-size:14px;
	font-weight:bold;
	color:#FFF;
	/*border:solid 1px red;*/
}
.retakeTestTxt{
	float:left;
	background-image:url(images/testBtns_retake.jpg);
	background-repeat:no-repeat;
	width:226px;
	height:89px;	
}
.retakeHiLight{
	float:left;
	background-image:url(images/testBtns_retakeHiLight.jpg);
	background-repeat:no-repeat;
	width:226px;
	height:89px;	
}
.continueTestClss{
	float:right;
	background-image:url(images/testBtns_continue.jpg);
	background-repeat:no-repeat;
	width:226px;
	height:89px;	
}
.continueTestHiLightClss{
	float:right;
	background-image:url(images/testBtns_continueHiLight.jpg);
	background-repeat:no-repeat;
	width:226px;
	height:89px;	
}
#retakeTestDiv{
	width:850px;
	height:350px;
	padding-top:15px;
	background-image:url(images/testBtns_cautionSign.jpg);
	background-repeat:no-repeat;
}
#retakeTestDiv p{
	color:#666;
	font-size:22px;
	padding-left:100px;
	padding-right:10px;
	margin-bottom:10px;
}
.prevScoreClss{
	color:#F00;
	font-size:30px;
	font-weight:bold;
}
#testInstructions{
	cursor:pointer;
	color:#FFF;	
}
#retakeTestInstructions{
	color:#FFF;
	font-style:italic;
	font-size:18px;
	border:solid 1px #333;
	padding-top:5px;
	padding-bottom:10px;
	background-color:#0F5073;
	margin-top:10px;
	text-align:center;
	cursor:default;
}
#btnContainer{
	width:650px;
	padding-left:80px;
	padding-top:20px;
	cursor:default;	
}
#testTitle{
	color:#FFF;
	background-color:#333;
	padding-left:5px;
	padding-top:2px;
	padding-bottom:2px;
	font-size:14px;
}
#bgWrapper{
	width:1000px;
	height:100%;
	margin:auto;
	/*border:solid 2px red;*/
	background-attachment: fixed;
	background-image:url(images/pageBG_default.jpg);
	background-repeat: no-repeat;
	background-position: center top;
}
.nextBtnTxtClss{
	padding-right:85px;
	color:#333;
}
#loadingTxt{
	font-size:30px;
	font-weight:bold;
	height:100px;
	width:850px;
	text-align:center;
	color:#666;
}

</style>
</head>

<body>
<div id="bgWrapper">
<div id="pageWrapper">
<div id="mainContent">
<div id="userDisplay">Welcome <?php echo $endUserObj->f_name . ' ' . $endUserObj->l_name . '!'; ?></div>
<div id="testTitle"></div>
<div id="testInstructions">View Test Instructions</div>
</div>
<div id="ansSummaryContainer"></div>
<div id="subAllAnsTxt">Please Answer All Test Questions</div>
<div id="subBtnContainer"><div id="loadingTxt">Sending Test Data...</div></div>
<div id="flaContainer">
  <object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="850" height="305">
    <param name="movie" value="testFeedback.swf" />
    <param name="quality" value="high" />
    <param name="wmode" value="opaque" />
    <param name="swfversion" value="8.0.35.0" />
    <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
    <param name="expressinstall" value="expressInstall.swf" />
    <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
    <!--[if !IE]>-->
    <object type="application/x-shockwave-flash" data="testFeedback.swf" width="850" height="305">
      <!--<![endif]-->
      <param name="quality" value="high" />
      <param name="wmode" value="opaque" />
      <param name="swfversion" value="8.0.35.0" />
      <param name="expressinstall" value="expressInstall.swf" />
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

<div id="preLoadImages"><img src="images/testBtns_retakeHiLight.jpg" /><img src="images/subAnsBtn_hiLight.jpg" /><img src="images/testBtns_continueHiLight.jpg" /></div>

</div>
</div>
<script type="text/javascript">
swfobject.registerObject("FlashID");
</script>
</body>
</html>