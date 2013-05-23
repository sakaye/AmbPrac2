<?php session_start();
define('DB_CONN','dbConnect.php');
require DB_CONN;
include('../utilities/safe_strings.php');
// =================== CHECK SESSION ID FOR USER ID ===========

// ============================================================
// sessions below were originally created in processors/educationCreator.php

if(isset($_POST['delQAndA'])){
	$qID = $_POST['qID'];
	$delQ = 'DELETE FROM test_questions WHERE tq_id = ' . $qID . ' LIMIT 1';
	$delQResult = $db->query($delQ);
	
	$delAns = 'DELETE FROM test_answers WHERE ta_q_link = ' . $qID;
	$delAnsResult = $db->query($delAns);
	
	echo json_encode("complete");
}