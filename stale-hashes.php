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
                function($t) {
                    return $t->infohash; 
                },
                call_user_func(function(array $st){
                    usort(
                        $st,
                        function($a, $b) {
                            if ($a->scraped_date == $b->scraped_date) {
                                return 0;
                            }
                            return ($a->scraped_date < $b->scraped_date) ? -1 : 1;
                        }
                    );
                    return $st;
                },
                getStale($handle))
            )
        );
    }
