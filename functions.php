<?php
require('vendor/autoload.php');

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

function upstreamAddress($addrType = null)
{
    if (is_null($addrType)) {
        return $_ENV['UPSTREAM_ADDR'];
    }

    return $_ENV['UPSTREAM_ADDR_' . $addrType];
}

function formatBytes($bytes, $precision = 2)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}

function formatTorrent($t)
{
    $seeders = -1;
    $leechers = -1;
    $completed = -1;
    $scraped_date = -1;
    foreach ($t->trackerData as $data) {
        if ($data->scraped_date > time() - 86400 * 100) {
            if ($data->complete > $seeders) {
                $seeders = $data->complete;
                $leechers = $data->incomplete;
                $completed = $data->downloaded;
                $scraped_date = $data->scraped_date;
            }
        }
    }
    $t->infohash = $t->_id;
    $t->seeders = $seeders;
    $t->leechers = $leechers;
    $t->completed = $completed;
    $t->scraped_date = $scraped_date;
    $t->dht_peers = $t->dhtData->peers;
    $t->dht_scraped = $t->dhtData->scraped_date;
    unset($t->_id);
    unset($t->trackers);
    unset($t->trackerData);
    unset($t->dhtData);
    return $t;
}


function secondsToTime($seconds)
{
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a d, %h h, %i m');
}

function jsonGet($address, $data)
{
    $context_options = array(
        'http' => array(
            'method' => 'GET',
            'header' => "Content-type: application/json\r\n"
                . "Content-Length: " . strlen($data) . "\r\n",
            'content' => $data
        )
    );
    $context = stream_context_create($context_options);
    return fopen($address, 'r', false, $context);
}
