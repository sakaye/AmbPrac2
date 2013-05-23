<?php require_once('Connections/conn_ambulatoryCare.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_conn_ambulatoryCare, $conn_ambulatoryCare);
$query_rs_getFlashClipData = "SELECT * FROM flash_display_types, flash_display_clips WHERE flash_display_clips.fdc_fdt_link = flash_display_types.fdt_id AND flash_display_clips.fdc_live = 'y' ORDER BY fdc_fdt_link ASC";
$rs_getFlashClipData = mysql_query($query_rs_getFlashClipData, $conn_ambulatoryCare) or die(mysql_error());
$row_rs_getFlashClipData = mysql_fetch_assoc($rs_getFlashClipData);
$totalRows_rs_getFlashClipData = mysql_num_rows($rs_getFlashClipData);

$numRecords = 'numRecords=' . urlencode($totalRows_rs_getFlashClipData);
$flashType= '';
$flashUrl= '';
$clipCounter = 0;
do {
    	$flashType.= '&flashType' . $clipCounter . '=' . urlencode($row_rs_getFlashClipData['fdt_id']);
		$flashUrl.= '&clipUrl' . $clipCounter . '=' . urlencode($row_rs_getFlashClipData['fdc_url']);
		$clipCounter +=1;
} while ($row_rs_getFlashClipData = mysql_fetch_assoc($rs_getFlashClipData));

echo $numRecords . $flashType . $flashUrl . '&swf0=clip1_sept2010c.swf&swf1=clip2_march11.swf&swf2=clip3_april_11.swf&swf3=clip4_april_11.swf&swf4=clip5.swf&swf5=clip6.swf&swf6=clip7_oct.swf';
//'&swf0=clip1_sept2010c.swf&swf1=clip2_7_28_10.swf&swf2=clip3.swf&swf3=clip4.swf&swf4=clip5.swf&swf5=clip6.swf&swf6=clip7.swf';
mysql_free_result($rs_getFlashClipData);
?>