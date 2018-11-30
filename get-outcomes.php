<?php
    require_once(__DIR__.'/userFuncs/outcomes.php');
    function json_response($message = null, $code = 200){
        // clear the old headers
        header_remove();
        // set the actual code
        http_response_code($code);
        // set the header to make sure cache is forced
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        // treat this as json
        header('Content-Type: application/json');
        $status = array(
            200 => '200 OK',
            400 => '400 Bad Request',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error'
        );
        // ok, validation error, or failure
        header('Status: '.$status[$code]);
        // return the encoded json
        return json_encode(array(
            'status' => $code < 300, // success or not?
            'message' => $message
        ));
    }
    if (empty($_POST)) {
        $_POST = json_decode(file_get_contents("php://input"), true) ? : [];
    }
    $outcomes = new Outcomes();
    
    echo json_response($outcomes->FindOutcomes($_POST['major'], $_POST['course']), 200)
?>