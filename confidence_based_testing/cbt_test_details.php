<?php session_start();
$edID = $_GET['edID'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ambulatory Practice</title>
<script type="text/javascript" src="../../../jquery.min.js"></script>
<script type="text/javascript" src="jscr/jquery.dataTables.min.js"></script>
<script type="text/javascript">
function init(){
	initiateTestData();
}
function initiateTestData(){
	var edID = new Object();
	edID['edID'] = initialEDID;
	$.post('cbt_details.php',edID,learnersLoaded,"json");
	
	var tempObj = new Object();
	tempObj['noVal']='true';
	$.post('comboEdListGrabber.php',tempObj,edListLoaded,"json");
}
function createTRData(edObj){
	$tempObj = '<tr><td>' + edObj.endUserVO.l_name + ', ' + edObj.endUserVO.f_name + ' ' + edObj.endUserVO.m_init  + '.</td>' +
			   '<td>' + edObj.endUserVO.title + '</td>' +
			   '<td>' + edObj.endUserVO.area + '</td>' +
			   '<td>' + edObj.endUserVO.mob + '</td>' +
			   '<td>' + edObj.endUserVO.dept + '</td>' +
			   '<td>' + edObj.preTestDate + '</td>' +
			   '<td>' + edObj.postTestDate + '</td>' +
			   '<td>' + edObj.saTestDate + '</td>' +
			   '</tr>';
	return $tempObj;
}
function grabConfLvls(){
	var edID = new Object();
	edID['edID'] = initialEDID;
	$.post('grabConfLvls.php',edID,confLvlsLoaded,"json");	
}
function confLvlsLoaded(result){
	if(result.standAloneLvl!='No Data'){
		var saConfLvl = result.standAloneLvl + '%';
	} else {
		var saConfLvl = result.standAloneLvl;
	}
	
	if(result.preLvl!='No Data'){
		var preConfLvl = result.preLvl + '%';
	} else {
		var preConfLvl = result.preLvl;
	}
	
	if(result.postLvl!='No Data'){
		var postConfLvl = result.postLvl + '%';
	} else {
		var postConfLvl = result.postLvl;
	}
	
	$('#saConfLvl').append('<div>Confidence Level = <span style="font-size:18px">' + saConfLvl + '</span></div>');
	$('#preConfLvl').append('<div>Confidence Level = <span style="font-size:18px">' + preConfLvl + '</span></div>');
	$('#postConfLvl').append('<div>Confidence Level = <span style="font-size:18px">' + postConfLvl + '</span></div>');
}
function formatTable(){
		$('#learnersTable').dataTable({
		"bPaginate": false,
		"bLengthChange": false,
		"bFilter": true,
		"bInfo": false,
		"bAutoWidth": false,
		"bJQueryUI": false,
		"asStripClasses":['tr1','tr2']
	});
	$('#learnersTable tr').mouseover(function(){
		$(this).addClass("hiLite");
	})
	$('#learnersTable tr').mouseout(function(){
		$(this).removeClass("hiLite");
	})
}
function learnersLoaded(result){
	loadAverages();
	grabConfLvls();
	$('#titleDescriptAuthor').append('<p id="tTitle">'+result[0].testTitle+'</p>');
	$('#titleDescriptAuthor').append('<p id="tDescript">' + result[0].testDescript + '</p>');
	$('#titleDescriptAuthor').append('<p id="tAuthor"><span style="color:#666">Test Author: </span>' + result[0].testAuthor + '</p>');
	learnersAC = result;
	var counter=0;
	$(result).each(function(){
		var tempObj = createTRData(result[counter]);
		$('#learnersTable').append(tempObj);
		counter+=1;
	})
	if(firstRun=='true'){
		formatTable();
	}
	
	
	
}
function loadAverages(){
	var postObj = new Object();
	postObj['avgs']='true';
	postObj['edID'] = initialEDID;
	$.post('cbt_test_avgs.php',postObj,averagesLoaded,"json");
}
function averagesLoaded(result){
	var avgSAScore = result[0];
	var avgPreScore = result[1];
	var avgPostScore = result[2];
	var saScoreDiv = '<div id="saScoreContainer">' +
					 	'<div id="saScoreAvg">' +
					   		'Average Stand-Alone Test Score: <span style="font-size:18px;">' + avgSAScore + '%</span> ' +
					 	'</div>' +
						'<div id="saConfLvl"></div>' +
						'<div style="clear:both"></div>' +
					 '</div>';
	 $('#testStatsContainer').append(saScoreDiv);
	 var preScoreDiv = '<div id="preScoreContainer">' +
	 						'<div id="preScoreAvg">' +
					   			'Average Pre Test Score: <span style="font-size:18px;">' + avgPreScore + '% </span>' +
					 		'</div>' +
							'<div id="preConfLvl"></div>' +
							'<div style="clear:both"></div>' +
						'</div>';
	 $('#testStatsContainer').append(preScoreDiv);
	
	var postScoreDiv = '<div id="postScoreContainer">' +
							'<div id="postScoreAvg">' +
					   			'Average Post Test Score: <span style="font-size:18px;">' + avgPostScore + '%</span> ' +
					 		'</div>' +
							'<div id="postConfLvl"></div>' +
							'<div style="clear:both"></div>' +
						'</div>';
	 $('#testStatsContainer').append(postScoreDiv);

}
function edListLoaded(result){
	var counter=0
	$(result).each(function(){
		var tempListItem = '<option value="'+result[counter].et_id+'">'+result[counter].et_title+' (' + result[counter].totalCompletions + ')</option>';
		$('#edCombo').append(tempListItem);
		counter+=1;
	})
	
	$('#edCombo').change(function(){
		/*
		firstRun='false';
		$('#learnersTableBody').empty();
		initialEDID = $(this).val();
		initiateTestData();
		*/
		window.location.href="cbt_test_details.php?edID="+$(this).val();
	})
	
}

var learnersAC = new Object();
var initialEDID = <?php echo $edID; ?>;
var firstRun='true';
$('document').ready(init);
</script>
<style type="text/css">
body{
	font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
}
p{
	margin:0px;	
}
#mainWrapper{
	width:1000px;
	border:solid 1px #A8A8A8;
	margin:auto;
}
.testInfoClss{
	padding:5px;
	width:990px;
	border:solid 1px #7A7A7A;
	background-image:url(images/learnerGraphics_testBanner.jpg);
	background-repeat:no-repeat;
	background-position:center;
}
#titleDescriptAuthor{
	width:575px;
	float:left;	
}
#testFilterContainer{
	float:right;
	width:400px;
}
#testStatsContainer{
	width:990px;
	border:solid 1px #CCC;	
	padding:5px;
	font-size:12px;
	background-color:#1A1A1A;
}
#tTitle{
	font-size:28px;
	color:#333;
}
#tDescript{
	color:#535353;	
	margin-left:10px;
}
#tAuthor{
	color:#069;
	font-size:11px;
	margin-left:10px;
}
.learnersTableClss{
	font-size:11px;
	font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
	border:solid 1px #999;
	width:1000px;
}
#learnersTable th{
	cursor:pointer;
	background-color:#069;
	color:#F4F4F4;
}
#learnersTable td{
	color:#404040;
	border: solid 1px #CCC;
}

.tr1{
	background-color:#EEF8FD;	
}
.tr2{
	background-color:#CAE9F9;
}
.hiLite{
	background-color:#FC0;
}
.edComboClss{
	border:solid 1px #09F;
	background-color:#E1EEFD;
	float:right;
	width:350px;
}
#learnerTableBanner{
	background-image:url(images/learnerGraphics_learnerCompBanner.jpg);
	background-repeat:no-repeat;
	width:1000px;
	height:51px;
}
#saScoreContainer{
	width:995px;
	border-bottom:solid 1px #666;
}
#saScoreAvg{
	float:left;
	width:350px;
	color:#9F0;
}
#saConfLvl{
	float:left;
	width:350px;
	color:#0CF;
}

#preScoreContainer{
	border-bottom:solid 1px #666;
	width:995px;	
}
#preScoreAvg{
	float:left;
	width:350px;
	color:#9F0;	
}
#preConfLvl{
	float:left;
	width:350px;
	color:#0CF;
}

#postScoreContainer{
	border-bottom:solid 1px #666;
	width:995px;	
}
#postScoreAvg{
	float:left;
	width:350px;
	color:#9F0;	
}
#postConfLvl{
	float:left;
	width:350px;
	color:#0CF;	
}
</style>
</head>

<body>
<div id="mainWrapper">
	<div id="primaryContainer">
    <div id="testInfo" class="testInfoClss">
    	<div id="titleDescriptAuthor"></div>
        <div id="testFilterContainer">
          <select id="edCombo" name="edCombo" class="edComboClss">
            <option value="0">Select a Different Test...</option>
          </select>
          <div style="clear:both"></div>
      </div>
        <div style="clear:both"></div>        
    </div>
    <div id="testStatsContainer">
       	
    </div>
    
    <div id="learnerTableBanner">
    
    </div>
    
    	<table id="learnersTable" class="learnersTableClss">
        	<thead>
            <tr>
            	<th>Name</th>
                <th>Title</th>
                <th>Area</td>
                <th>Location</th>
                <th>Dept</th>
                <th>Pre Test Date</th>
                <th>Post Test Date</th>
                <th>Stand-Alone Test Date</th>
             </tr>
             </thead>
             <tbody id="learnersTableBody">
             </tbody>
        </table>
   </div>
</div>
</body>