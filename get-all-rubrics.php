<?php
session_start();
require_once(__DIR__.'/http/jsonResponse.php');
require_once(__DIR__.'/userFuncs/rubrics.php');
$rubrics = new Rubric();
echo json_response($rubrics->GetPastAndCurrentRubrics($_POST['major'], $_POST['course'], $_POST['outcomeId']), 200)
?>