<?php
function makeSafe($theString){
	
	$theStringObj = strip_tags($theString);
	
	return $theStringObj;	
}
?>