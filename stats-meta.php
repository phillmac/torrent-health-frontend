<?php

use MathPHP\Statistics\Descriptive;

require('functions.php');
$address = upstreamAddress();
$handle = FALSE;

$handle = fopen($address, 'r');

if ( !$handle ) {
    http_response_code(500);
    echo 'Error';
}  else {
    $torrents = handleGetFormatted($handle);
    $stale = array_filter ($torrents, function($t) {
        return ($t->scraped_date + 10800) < time();
    });
    $now = time();
    $ages = array_map(function($t) {
        return $t->scraped_date;
    }, $torrents);

    $oldest = min($ages);

    $percentile_age = intval(Descriptive::percentile($ages, 5));

    $noseeds = array_filter($torrents, function($t) {
        return $t->seeders === 0 ;
    });

    $noleechers = array_filter($torrents, function($t) {
        return $t->leechers === 0 ;
    });

    $weaklyseeded = array_filter($torrents, function($t) {
        return $t->seeders < 3 ;
    });

?>

<!DOCTYPE html>
<html lang="en">
    <?php require('head.php'); ?>
    <body>
        <h1>Torrent Health Tracker</h1>
        <h2>Updated: <?= (new \DateTime())->format('Y-m-d H:i:s e'); ?></h2>
        <p>Stale count: <?= count($stale); ?></p>
        <p>Oldest: <?= secondsToTime($now - $oldest); ?></p>
        <p>(95% Age): <?= secondsToTime($now - $percentile_age); ?></p>
        <p>No seeders: <?= count($noseeds); ?></p>
        <p>No leechers: <?= count($noleechers); ?></p>
        <p>Weakly seeded (< 3 seeds): <?= count($weaklyseeded); ?></p>
    </body>
</html>

<?php
    }
?>