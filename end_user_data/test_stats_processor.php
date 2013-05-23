<?php
function subQStats($testID, $qID, $correctStatus, $confLevel){
	$tQ = 'SELECT * FROM q_score_stats WHERE $qID = qss_test_link';
	$tQResult = $db->query($tQ);
	echo($tQResult->num_rows);
}
?>