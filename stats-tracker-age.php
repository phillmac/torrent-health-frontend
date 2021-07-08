<?php
    require('functions.php');
    $address = upstreamAddress();
    $handle = FALSE;

    $handle = fopen($address, 'r');

    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json; charset=utf-8');

    validateHandle($handle);

    echo json_encode(
        handleGetFormattedAge($handle)
    );

