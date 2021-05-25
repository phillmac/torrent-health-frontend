<?php
    require('functions.php');

    $address = upstreamAddress('HASH');
    $handle = FALSE;

    if (! isset($_REQUEST['hash'])) {
        http_response_code(400);
        echo "Missing 'hash' param";
        die();
    }

    $handle = jsonGet($address, json_encode(array('hash' => $_REQUEST['hash'])));

    if ( !$handle ) {
        http_response_code(500);
        echo "Error";
        die();
    }  else {
        $torrent = json_decode(stream_get_contents($handle));
    }
?>

<!DOCTYPE html>
<html lang="en">
    <?php require('head.php'); ?>
    <body>
        <h1>Torrent Health Tracker</h1>
        <h2>Updated: <?php echo (new \DateTime())->format('Y-m-d H:i:s e'); ?></h2>
        <h3>
            <p>Infohash: <?= $torrent->_id; ?></p>
            <p>Name: <?= $torrent->name; ?></p>
            <p>Link: <?= $torrent->link; ?></p>
            <p>Created: <?= $torrent->created_unix; ?></p>
            <p>Size: <?= formatBytes($torrent->size_bytes);  ?></p>
            <p>Type: <?= $torrent->type ?></p>
        </h3>
        <?php require('table-trackers.php'); ?>
    </body>
</html>
