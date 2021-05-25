<?php
    require('functions.php');

    $address = upstreamAddress('HASH');
    $handle = FALSE;

    if (! isset($_REQUEST['hash'])) {
        http_response_code(400);
        echo json_encode(
            array(
                'status' => 'error',
                'reason' => "Missing 'hash' param"
            )
        );        
        die();
    }
    
    $handle = jsonGet($address, json_encode(array('hash' => $_REQUEST['hash'])));

    
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json; charset=utf-8');

    if (! $handle) {
        http_response_code(500);
        echo json_encode(
            array(
                'status' => 'error',
                'reason' => 'Invalid upstream handle'
            )
        );
    }  else {
        while(!feof($handle)) {
            echo fgets($handle, 1024);
        }
    }
