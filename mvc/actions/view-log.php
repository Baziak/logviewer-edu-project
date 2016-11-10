<?php

$logFile = isset($_GET['logFile']) ? $_GET['logFile'] : '';

return ['logFile' => $logFile];