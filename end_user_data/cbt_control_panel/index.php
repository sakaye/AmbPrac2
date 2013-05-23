<?php
ini_set('include_path',$_SERVER['DOCUMENT_ROOT'].'/ZendFramework/library/');
define('CBT_CONN','dbConnect.php');
include('classes/EducatorVO.php');
include('classes/EducationTableVO.php');
include('classes/EducationSourceVO.php');
include('classes/TopicAreaVO.php');
include('classes/CBTCompletionsVO.php');
include('classes/QScoreStatsVO.php');
include('classes/TestQuestionsVO.php');
include('classes/EndUserVO.php');
include('classes/LearnerVO.php');
include('classes/TestQuestionVO.php');
include('classes/AnsObjVO.php');
include('classes/QAObjVO.php');
include('classes/TestInstructionsVO.php');
include('classes/AdminMessagesVO.php');

require('Zend/Amf/Server.php'); 

$server = new Zend_Amf_Server();
require('services/CBT_CP_Srvc.php');

$server->setClass("CBT_CP_Srvc"); 
$server->setClassMap("EducatorVO","classes.EducatorVO");
$server->setClassMap("EducationTableVO","classes.EducationTableVO");
$server->setClassMap("EducationTableVO","classes.EducationSourceVO");


echo $server->handle();
?>