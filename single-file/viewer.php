<?php

header('X-Memory-Usage-Start: ' . memory_get_peak_usage());

error_reporting(E_ALL);
ini_set("display_errors", 1);

$logFilePath = '../logs/agn.localhost-error.log';

function searchFile($filePath, $searchString, $offset = 0, $limit = 10)
{
    $f = fopen($filePath, "r");

    $matchedLines = [];

    while (($line = fgets($f)) !== false) {
        if (matchText($line, $searchString)) {

            if ($offset > 0) {
                $offset--;
                continue;
            }

            $matchedLines[] = $line;

            if (count($matchedLines) == $limit) {
                break;
            }
        }
    }

    return $matchedLines;
}

function searchFile2($filePath, $searchString, $offset = 0, $limit = 10)
{
    $lines = explode("\n", file_get_contents($filePath));

    $matchedLines = [];

    foreach ($lines as $line) {
        if (matchText($line, $searchString)) {

            if ($offset > 0) {
                $offset--;
                continue;
            }

            $matchedLines[] = $line;

            if (count($matchedLines) == $limit) {
                break;
            }
        }
    }

    return $matchedLines;
}

function searchFile3($filePath, $searchString, $offset = 0, $limit = 10)
{
    $lines = file($filePath);

    $matchedLines = [];

    foreach ($lines as $line) {
        if (matchText($line, $searchString)) {

            if ($offset > 0) {
                $offset--;
                continue;
            }

            $matchedLines[] = $line;

            if (count($matchedLines) == $limit) {
                break;
            }
        }
    }

    return $matchedLines;
}


function matchText($text, $searchString)
{
    return empty($searchString) || strpos($text, $searchString) !== false;
}

if (isset($_GET['action']) && $_GET['action'] == 'search') {

    $searchString = isset($_GET['query']) ? $_GET['query'] : '';
    $offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
    $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

    header('Content-Type: application/json');

    ob_start();
    echo json_encode(searchFile($logFilePath, $searchString, $offset, $limit));
    header('X-Memory-Usage-End: ' . memory_get_peak_usage());
    ob_end_flush();
    exit;
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title></title>
    <meta charset="utf-8" />
    <script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
</head>
<body>

<form id="searchForm">
    <input id="searchInput" type="text" />
    <button id="searchSubmitButton" type="submit">Search</button>
</form>

<div id="itemsList">

</div>

<button id="loadMoreButton" type="submit">More</button><span id="noMoreMessage">No more data</span>

<script>

    var itemsLimit = 10;

    var loadedItems = 0;

    function appendData(data) {
        var $list = $('#itemsList');

        if (Array.isArray(data) && data.length > 0) {
            for (var i in data) {
                $('<p>' + data[i] + '</p>').appendTo($list);
                loadedItems++;
            }
            $("#noMoreMessage").css("display", "none");

        } else {
            $("#noMoreMessage").css("display", "inline");
        }

        $("#loadMoreButton").css("display", "inline");
    }

    function clearData() {
        loadedItems = 0;
        $('#itemsList').empty();
    }

    function loadData(offset, limit, callback) {
        $.get(
            'viewer.php', {
                action: 'search',
                query: $('#searchInput').val(),
                offset: offset,
                limit: limit
            },
            callback,
            'json'
        );
    }

    var loadedItems = 0;

    function loadMoreData(limit, callback) {
        loadData(loadedItems, limit, callback)
    }

    $('#searchForm').on('submit', function(event) {
        event.preventDefault();

        loadData(0, itemsLimit, function (data) {

            clearData();
            appendData(data);
        });
    });



    $('#loadMoreButton').on('click', function() {

        loadMoreData(itemsLimit, function (data) {

            appendData(data);
        });
    });

</script>

<style>
    #searchInput {
        border: 1px solid #8f8f8f;
        border-radius: 3px;
        font-size: 20px;
        padding: 8px;
        width: 400px;
    }

    #searchSubmitButton {
        border: 1px solid #8f8f8f;
        border-radius: 3px;
        font-size: 20px;
        padding: 8px;
    }
    #itemsList>p {
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 16px;
        padding: 8px;
        margin: 0;
    }

    #itemsList>p:hover {
        background-color: #FFF8F0;
    }

    #loadMoreButton {
        border: 1px solid #8f8f8f;
        border-radius: 3px;
        font-size: 16px;
        padding: 8px;
        background-color: #FFF8F0;
        margin: 8px;
        display: none;

    }
    #noMoreMessage {
        display: none;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
</style>
</body>
