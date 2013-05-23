<?php
define('CONN_EU','dbConnect.php');
require CONN_EU;
if(isset($_GET['edID'])){
	$edID = $_GET['edID'];
} else {
	$edID = '';
}
$edQ = 'SELECT * FROM education_completions WHERE ec_ed_link = ' . $edID . ' LIMIT 1';
$edQResult = $db->query($edQ);
if($edQResult->num_rows == 0){
	$edQInsert = 'INSERT INTO education_completions (ec_ed_link,ec_count) values (' . $edID . ',1)';
	$edQInsertResult = $db->query($edQInsert);	
} else {
	$row = $edQResult->fetch_assoc();
	$returnedEdID = $row['ec_id'];
	$newCount = $row['ec_count'] + 1;
	$updateEdQ = 'UPDATE education_completions SET ec_count=' . $newCount . ' WHERE ec_id = ' . $returnedEdID; 	
	$updateEdQResult = $db->query($updateEdQ);
}
?>
<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ambulatory Practice</title>
<style type="text/css">
body{
	font-family:Arial, Helvetica, sans-serif;
}
#mainWrapper{
	width:650px;
	border:solid 1px #999;
	min-height:500px;
}
#subContent p{
	text-align:center;	
}
</style>
</head>

<body>
<div id="mainWrapper">
<h2 style="text-align:center; color:#06C; padding-top:15px;">Thank you for completing the education module!</h2>

<div id="subContent">
<p><?php //if($_GET['edID'] == 10){ echo 'Please print out the Flu Declination here'; } ?></p>
<p>You have completed this module, you may now close the window, thank you!</p>
</div>

</div>
</body>
</html>