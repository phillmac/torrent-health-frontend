<?php
    require('functions.php');

    $address = upstreamAddress('HASH');
    $handle = FALSE;

    if (isset($_REQUEST['hash'])) {
        $handle = jsonGet($address, json_encode(array('hash' => $_REQUEST['hash'])));
    }
    else {
        $params = json_decode(file_get_contents('php://input'), true);
        if (isset($params['hash'])) {
            $handle = jsonGet($address, json_encode(array('hash' => $params['hash'])));
        }
    }
    
    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json; charset=utf-8');

    if ( !$handle ) {
        http_response_code(500);
        json_encode(array('status' => 'error'));
    }  else {
        while(!feof($handle)) {
            echo fgets($handle, 1024);
        }
    }
