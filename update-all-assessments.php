<?php
session_start();
require_once(__DIR__.'/http/jsonResponse.php');
require_once(__DIR__.'/userFuncs/assessmentPlans.php');
$assessmentPlans = new AssessmentPlans();
echo json_response($assessmentPlans->UpdateAssessments($_POST['pastSectionId'], $_POST['major'], $_POST['course']), 200)
?>