<?php
    require('functions.php');
    $address = upstreamAddress();
    $handle = FALSE;

    $handle = fopen($address, 'r');

    if ( !$handle ) {
        http_response_code(500);
    }  else {
        echo json_encode(
            getFiltered($handle, 
                $_REQUEST['propname'],
                $_REQUEST['comp'],
                $_REQUEST['value']
            )
        );
    }





