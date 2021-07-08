<?php
require "vendor/autoload.php";
require "exceptions.php";

use Symfony\Component\Dotenv\Dotenv;
use Exceptions\FilterException as FilterException;
use MathPHP\Statistics\Descriptive;


$dotenv = new Dotenv();
$dotenv->load(__DIR__ . "/.env");

function upstreamAddress($addrType = null)
{
    if (is_null($addrType)) {
        return $_ENV["UPSTREAM_ADDR"];
    }

    return $_ENV["UPSTREAM_ADDR_" . $addrType];
}

function validateHandle($handle)
{
    if ( !$handle ) {
        http_response_code(500);
        echo json_encode(
            array(
                'status' => 'error',
                'reason' => 'Invalid upstream handle'
            )
        );
        die();
    }
}

function proxyUpstream($upstream) {
    $address = upstreamAddress($upstream);
    $handle = FALSE;

    $handle = fopen($address, 'r');

    header("Access-Control-Allow-Origin: *");
    header('Content-Type: application/json; charset=utf-8');

    validateHandle($handle);

    while(!feof($handle)) {
        echo fgets($handle, 1024);
    }
}

function formatBytes($bytes, $precision = 2)
{
    $units = ["B", "KB", "MB", "GB", "TB"];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . " " . $units[$pow];
}

function formatTorrent($t)
{
    $seeders = -1;
    $leechers = -1;
    //$completed = -1;
    $scraped_date = -1;
    foreach ($t->trackerData as $data) {
        if ($data->scraped_date > time() - 86400 * 100) {
            if ($data->complete > $seeders) {
                $seeders = $data->complete;
                $leechers = $data->incomplete;
                // $completed = $data->downloaded;
                $scraped_date = $data->scraped_date;
            }
        }
    }
    $t->infohash = $t->_id;
    $t->seeders = $seeders;
    $t->leechers = $leechers;
    // $t->completed = $completed;
    $t->scraped_date = $scraped_date;
    $t->dht_peers = $t->dhtData->peers;
    $t->dht_scraped = $t->dhtData->scraped_date;
    unset($t->_id);
    unset($t->trackers);
    unset($t->trackerData);
    unset($t->dhtData);
    return $t;
}


function formatTorrentAge($t)
{
    $oldest = -1;
    $newest = -1;
    $tracker_ages = [];
    foreach ($t->trackerData as $data) {
        if ($data->scraped_date > time() - 86400 * 100) {
            array_push($tracker_ages, $data->scraped_date);
            if ($oldest > $data->scraped_date) {
                $oldest = $data->scraped_date;
            }
            if ($newest < $data->scraped_date) {
                $newest = $data->scraped_date;
            }
        }
    }
    $t->infohash = $t->_id;
    $t->oldest = $oldest;
    $t->newest = $newest;
    $t->average = array_sum($tracker_ages) / count($tracker_ages);
    $t->percentile_age = intval(Descriptive::percentile($tracker_ages, 95));
    unset($t->_id);
    unset($t->trackers);
    unset($t->trackerData);
    unset($t->dhtData);
    return $t;
}

function secondsToTime($seconds)
{
    $dtF = new \DateTime("@0");
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format("%a d, %h h, %i m");
}

function jsonGet($address, $data)
{
    $context_options = [
        "http" => [
            "method" => "GET",
            "header" =>
                "Content-type: application/json\r\n" .
                "Content-Length: " .
                strlen($data) .
                "\r\n",
            "content" => $data,
        ],
    ];
    $context = stream_context_create($context_options);
    return fopen($address, "r", false, $context);
}

function getStale($handle, $max_age = 10800)
{
    return array_filter(
        array_map("formatTorrent", json_decode(stream_get_contents($handle))),
        function ($t) {
            return ($t->scraped_date + 10800) < time();
        }
    );
}

function applyFilter($items, $propname, $comp, $value)
{
    $comparisons = [
        "==" => function ($a, $b) {
            return $a == $b;
        },
        "<=" => function ($a, $b) {
            return $a <= $b;
        },
        ">=" => function ($a, $b) {
            return $a >= $b;
        },
        "<" => function ($a, $b) {
            return $a < $b;
        },
        ">" => function ($a, $b) {
            return $a > $b;
        },
        "!=" => function ($a, $b) {
            return $a != $b;
        },
        "<>" => function ($a, $b) {
            return $a != $b;
        },
        "<=>" => function ($a, $b) {
            return $a <=> $b;
        },
    ];

    $propnames = [
        "infohash",
        "seeders",
        "leechers",
        "scraped_date",
        "dht_peers",
        "dht_scraped",
        "type",
        "size_bytes",
        "name",
    ];

    if (!array_key_exists($comp, $comparisons)) {
        throw new FilterException("Invalid comparison");
    }

    if (!in_array($propname, $propnames)) {
        throw new FilterException("Invalid propertyname");
    }
    $compare = $comparisons[$comp];
    return array_filter($items, function ($t) use (
        $compare,
        $propname,
        $value
    ) {
        return $compare($t->{$propname}, $value);
    });
}

function buildFilters()
{
    $propnames = $_REQUEST["propname"];
    $comps = $_REQUEST["comp"];
    $values = $_REQUEST["value"];

    if (!is_array($propnames)) {
        $propnames = [$propnames];
    }
    if (!is_array($comps)) {
        $comps = [$comps];
    }
    if (!is_array($values)) {
        $values = [$values];
    }
    $filter_list = [];

    while ($propname = array_shift($propnames)) {
        $filter = new stdClass();
        $filter->propname = $propname;

        if (count($comps) > 1) {
            $filter->comp = array_shift($comps);
        } else {
            $filter->comp = $comps[0];
        }
        if (count($values) > 1) {
            $filter->value = array_shift($values);
        } else {
            $filter->value = $values[0];
        }
        array_push($filter_list, $filter);
    }
    return $filter_list;
}

function getFiltered($items, $filters)
{
    while ($filter_item = array_pop($filters)) {
        $items = applyFilter(
            $items,
            $filter_item->propname,
            $filter_item->comp,
            $filter_item->value
        );
    }

    return $items;
}

function handleGetFormatted($handle)
{
    return array_map(
        "formatTorrent",
        json_decode(stream_get_contents($handle))
    );
}

function handleGetFormattedAge($handle)
{
    return array_map(
        "formatTorrentAge",
        json_decode(stream_get_contents($handle))
    );
}