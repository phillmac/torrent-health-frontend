<?php
    require('functions.php');

    $address = upstreamAddress('HASH');
    $handle = FALSE;

    $handle = jsonGet($address, json_encode(array('hash' => $_GET['infohash'])));

    header('Content-Type: application/json; charset=utf-8');

    if ( !$handle ) {
            echo '{"status": "error"}';
    }  else {
        while(!feof($handle)) {
            echo fgets($handle, 1024);
        }
    }
