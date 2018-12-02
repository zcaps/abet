<?php
    session_start();
    require_once(__DIR__.'/userFuncs/outcomes.php');
    require_once(__DIR__.'/http/jsonResponse.php');
    if (empty($_POST)) {
        $_POST = json_decode(file_get_contents("php://input"), true) ? : [];
    }
    $outcomes = new Outcomes();
    
    echo json_response($outcomes->FindOutcomes($_POST['major'], $_POST['course']), 200)
?>