<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once 'lib/Controller.php';

$controller = new Controller([
    'actionsDirectory' => './actions',
    'viewsDirectory' => './views',
    'modelsDirectory' => './models',
    'logsDirectory' => '../logs',
    'defaultAction' => 'list-logs'
]);

$controller->run();