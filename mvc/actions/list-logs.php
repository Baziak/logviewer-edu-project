<?php
require_once $this->modelsDirectory
    . DIRECTORY_SEPARATOR . 'LogsListModel.php';

$logsList = new LogsListModel($this->logsDirectory);

return ['logs' => $logsList->getLogNames()];