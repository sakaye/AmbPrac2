<?php session_start(); ?>


<?php 
require('dbConnect.php');
include("../../../footerInfo.php"); ?>
<?php include("../../../class.breadcrumb.inc.php"); ?>


<?php
// ---------- ED COUNTER ----------------------------------
if(isset($_GET['lid'])){
	$getEdCount = 'SELECT * FROM education_hit_count WHERE ehc_ed_link = ' . $_GET['lid'] . ' LIMIT 1';
	$getEdCountResult = $db->query($getEdCount);
	$tempCount = 0;
	while($row = $getEdCountResult->fetch_assoc()){
		$tempCount = $row['ehc_count'];
	}
	$tempCount +=1;
	if($getEdCountResult->num_rows ==0){
		$addEdQ = 'INSERT INTO education_hit_count (ehc_ed_link,ehc_count) VALUES (' . $_GET['lid'] . ',0)';
		$addEdQResult = $db->query($addEdQ);	
	}
	
	$subCountQ = 'UPDATE education_hit_count SET ehc_count = ' . $tempCount . ' WHERE ehc_ed_link = ' . $_GET['lid'] . ' LIMIT 1';
	$subCountQResult = $db->query($subCountQ);
}
// ---------------- END OF ED COUNTER -----------------------
?>

<?php
$breadcrumb = new breadcrumb;
$breadcrumb->_toSpace = TRUE;

$allEdQ = 'SELECT * FROM links_level2 WHERE links_level2.education = "y" ORDER BY title';
$allEdQResult = $db->query($allEdQ);
function checkSelected($linkID){
	if(isset($_GET['lid'])){
		if($linkID == $_GET['lid']){
			return 'class="hiLight"';
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ambulatory Practice</title>
<link href="../../../css/apStyle1.css" rel="stylesheet" type="text/css" />
<script src="../../../Scripts/swfobject_modified.js" type="text/javascript"></script>

<script type="text/javascript" src="../../../jquery.min.js"></script>
<script type="text/javascript">
function init(){
	$.post('cbtGrabber.php','',edObjsLoaded,"json");
}

function edObjsLoaded(result){
	var counter=0;
	$(result).each(function(){
		$('#cbtObjWrapper').append('<div>' + result[counter].et_title + '</div>');
		counter +=1;
	})
}

$('document').ready(init);
</script>
<?php
//Sample CSS
echo "
<style>
#breadcrumb ul li{
   list-style-image: none;
   display:inline;
   padding: 0 3px 0 0;
   margin: 3px 0 0 0;
}
#breadcrumb ul{
   margin:0;padding:0;
   list-style-type: none;
   padding-left: 1em;
}
</style>
";

?>
</head>

<body>
<?php // -------------------- BEGINNING OF MAIN PAGE WRAPPER ---------------------------------------------------------- ?>
<div id="mainPageWrapper">

<?php // -------------------- beginning of banner div ------------ ?>
<div id="banner"></div>
<?php // --------------------- end of banner ----------------------- ?>


<?php // ======================== start of search div =============================== ?>
<div id="searchAndDateBox">
	<div id="time"><?php echo DATE('d'); ?></div><span style="float:left; padding-top:4px; font-size:16px; font-weight:500; color:#8B8B8B;"><?php echo date('l, F jS, Y'); ?></span>
    <div id="searchBox"><form style="margin:0" action="../../../searchResults.php" method="get" name="srchFrm" target="_self"><div id="searchImage"></div><input name="srchTerm" type="text" id="searchInput" size="25" /></form></div>
    <div style="clear:both;"></div>
</div>
<?php // ------------------- end of search div ----------------------------- ?>

<?php // --------------------- breakcrumb ---------------------------------------------- ?>
<div id="breadCrumbBox"><?php echo $breadcrumb->show_breadcrumb(); ?></div>
<?php // --------------- end of  breakcrunb ----------------------------------------- ?>

<?php // --------------------------- MAIN CONTENT DIV -------------------------------- ?>
<div id="mainContent">

<!--<div id="myTestDiv">
<div style="margin-left:2px"><img src="assets/webLayout/testContent.jpg" width="950" height="779" /></div>
</div>-->
<div id="mainContentGroup" style="background-color:#FFF; background-image:url(../../../assets/images/onlineEd_03.jpg); background-position:bottom right; background-repeat:no-repeat;"><?php // ------------------------------------------------------------------ ?>

<div id="mainContentWrapper">
 
<div id="cbtObjWrapper"></div>


</div><?php // --------------------------------------------------------------------------------------- ?>


</div>
<?php // --------------------------- END OF MAIN CONTENT DIV ------------------------ ?>

<?php // ----------- FOOTER ------------------------------------ ;?>
<div id="footer"></div><div id="footerText"><?php getFooterInfo();?></div>
<?php // ---------- end of footer ------------------------------- ?>

<div style="clear:both"></div>
</div>
<?php // -------------------- END OF MAIN PAGE WRAPPER -------------------------------------------------------------- ?>


</body>
</html>