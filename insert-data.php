<?php
session_start();
require_once(__DIR__.'/http/jsonResponse.php');
require_once(__DIR__.'/userFuncs/results.php');
$results = new Results();
$results = $results->InsertResults($_POST);
echo json_response($results, 200);
?>