<?php
require('areasAndTitles.php');

$allAreasResult = getAreas();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Areas and MOBs - ambulatorypractice.org</title>
<style type="text/css">
body,td,th {
	font-family: "Lucida Console", Monaco, monospace;
}
</style>
</head>

<body>
<table>
<tr><td>Areas and MOBs</td></tr>
<?php
while($row = $allAreasResult->fetch_assoc()){
	echo '<tr><td>&nbsp;</td></tr><tr><td style="background-color:#F4F4F4; border:solid 1px #666">' . $row['eua_title'] . '</td></tr>';
	$tempMOBList = getThisMOB($row['eua_id']);
	while($row = $tempMOBList->fetch_assoc()){
		echo '<tr><td>&nbsp;&nbsp;&nbsp; - ' . $row['eum_title'] . '</td></tr>';
	}
}
?>
</table>

</body>
</html>