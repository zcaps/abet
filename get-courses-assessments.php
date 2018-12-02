<?php
session_start();
require_once(__DIR__.'/http/jsonResponse.php');
require_once(__DIR__.'/userFuncs/assessmentPlans.php');
$assessmentPlans = new AssessmentPlans();
echo json_response($assessmentPlans->GetAllSectionsWithAssessments($_POST['major'], $_POST['course'], $_POST['outcomeId']), 200)
?>