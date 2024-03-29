<?php
    require('functions.php');
    $address = upstreamAddress();
    $handle = FALSE;

    $handle = fopen($address, 'r');

    if ( !$handle ) {
        http_response_code(500);
        echo 'Error';
        die();
    }

    $torrents = getStale($handle);
?>

<!DOCTYPE html>
<html lang="en">
    <?php require('head.php'); ?>
    <body>
        <h1>Torrent Health Tracker</h1>
        <h2>Updated: <?php echo (new \DateTime())->format('Y-m-d H:i:s e'); ?></h2>
        <?php require('table.php'); ?>
    </body>
</html>
