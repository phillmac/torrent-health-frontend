<?php
    require('functions.php');

    $address = upstreamAddress('EVENTS');
    $handle = FALSE;

    $handle = fopen($address, 'r');

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