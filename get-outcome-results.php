<?php
session_start();
require_once(__DIR__."/userFuncs/results.php");
require_once(__DIR__."/http/jsonResponse.php");
$results = new Results();
echo json_response($results->outcomeResults($_POST['major'], $_POST['course'], $_POST['outcomeId']), 200);
?>