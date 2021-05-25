<?php
    require('functions.php');
    $address = upstreamAddress();
    $handle = FALSE;

    $handle = fopen($address, 'r');

    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json; charset=utf-8');

    if ( !$handle ) {
        http_response_code(500);
        echo json_encode(
            array(
                'status' => 'error',
                'reason' => 'Invalid upstream handle'
            )
        );
        die();
    try {
        echo json_encode(
            getFiltered(
                handleGetFormatted($handle),
                buildFilters()
            )
        );
    } catch (FilterException $e) {
        http_response_code(400);
        echo json_encode(
            array(
                'status' => 'error',
                'reason' => $e->getMessage()
            )
        );
    }





