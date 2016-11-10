<?php

require_once 'LogModel.php';

class LogsListModel
{
    protected $logsDirectory = '';

    public function __construct($logsDirectory)
    {
        $this->logsDirectory = $logsDirectory;
    }

    public function getLogNames()
    {
        return array_diff(scandir($this->logsDirectory), ['.', '..']);
    }

    public function getLogModel($logName)
    {
        return new LogModel(
            $this->logsDirectory . DIRECTORY_SEPARATOR . $logName
        );
    }
}