<?php
    require('functions.php');

    $address = upstreamAddress('HASH');
    $handle = FALSE;

    if (isset($_GET['hash'])) {
        $handle = jsonGet($address, json_encode(array('hash' => $_GET['hash'])));
    }
    else {
        $params = json_decode(file_get_contents('php://input'), true);
        if (isset($params['hash'])) {
            $handle = jsonGet($address, json_encode(array('hash' => $params['hash'])));
        }
    }
    header('Content-Type: application/json; charset=utf-8');

    if ( !$handle ) {
            echo '{"status": "error"}';
    }  else {
        while(!feof($handle)) {
            echo fgets($handle, 1024);
        }
    }
