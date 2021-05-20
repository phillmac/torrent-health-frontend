<?php
    require('functions.php');
    $address = upstreamAddress();
    $handle = FALSE;

    $handle = fopen($address, 'r');

    header('Content-Type: application/json; charset=utf-8');

    if ( !$handle ) {
        echo '{"status": "error"}';
    }  else {
        echo json_encode(
            array_map(
                'formatTorrent',
                json_decode(
                    stream_get_contents($handle)
                )
            )
        );
    }
