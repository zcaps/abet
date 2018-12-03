<?php
session_start();
require_once(__DIR__.'/http/jsonResponse.php');
require_once(__DIR__.'/userFuncs/assessmentPlans.php');
$assessmentPlans = new AssessmentPlans();
echo json_response($assessmentPlans->UpdateOneAssessment($_POST['pastSectionId'], $_POST['assessmentDescription'], $_POST['major'], $_POST['outcomeId']), 200)
?>