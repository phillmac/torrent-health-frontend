<?php
    require('functions.php');

    $address = upstreamAddress();
    $handle = FALSE;

    $handle = fopen($address, 'r');

    header('Content-Type: application/json; charset=utf-8');

    if ( !$handle ) {
            echo '{"status": "error"}';
    }  else {
        $torrents = array_filter (
            array_map(formatTorrent, json_decode(stream_get_contents($handle))),
            function($t) {
                return ($t->scraped_date + 10800) < time();
        });
        echo json_encode($torrents);
    }
