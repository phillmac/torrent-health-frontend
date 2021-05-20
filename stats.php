<?php
    require('functions.php');

    $address = upstreamAddress();
    $handle = FALSE;

    $handle = fopen($address, 'r');

    header('Content-Type: application/json; charset=utf-8');

    if ( !$handle ) {
        http_response_code(500);
        json_encode(array('status' => 'error'));
    }  else {
        while(!feof($handle)) {
            echo fgets($handle, 1024);
        }
    }
