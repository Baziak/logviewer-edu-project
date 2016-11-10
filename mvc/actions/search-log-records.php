<?php

require_once $this->modelsDirectory
    . DIRECTORY_SEPARATOR . 'LogsListModel.php';

$logFile = isset($_GET['logFile']) ? $_GET['logFile'] : '';
$searchString = isset($_GET['query']) ? $_GET['query'] : '';
$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;

$logsList = new LogsListModel($this->logsDirectory);

$records = $logsList
    ->getLogModel($logFile)
    ->search($searchString, $offset, $limit);

header('Content-Type: application/json');
echo json_encode($records);