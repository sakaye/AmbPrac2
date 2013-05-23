<?php session_start();

define('DB_CONN','dbConnect.php');

require DB_CONN;


$fName = $_SESSION['usersNameForResultsFName'];

$lName = $_SESSION['usersNameForResultsLName'];

$mName = $_SESSION['usersNameForResultsMName'];

$testTitle = $_SESSION['edTitle'];



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Ambulatory Practice</title>

<script type="text/javascript" src="../jquery.min.js"></script>

<script type="text/javascript">



</script>

<style type="text/css">

.instructions{

	font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;

	font-size:20px;

	color:#666;

}

#mainWrapper{

	width:800px;

	text-align:center;	

}

.mainHeader{

	font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;

	color:#069;

}

</style>

</head>



<body style="margin:auto; width:800px;">

<div id="mainWrapper">

<h2 class="mainHeader">Thank you <?php echo $fName; ?>! You have completed the <?php echo $testTitle; ?>  <?php $prePostMessage; ?> test.<br /><br />You may close this window.</h2>

</div>

</body>

</html>