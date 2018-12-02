<?php
require_once(__DIR__.'/http/jsonResponse.php');
require_once(__DIR__.'/userFuncs/assessmentPlans.php');
$assessmentPlans = new AssessmentPlans();

echo json_response($assessmentPlans->AssessmentTypes(), 200);
?>