<?php

class LogModel
{
    protected $filePath = '';

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    function search($searchString, $offset = 0, $limit = 10)
    {
        $f = fopen($this->filePath, "r");

        $matchedLines = [];

        while (($line = fgets($f)) !== false) {
            if ($this->matchText($line, $searchString)) {

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

    protected function matchText($text, $searchString)
    {
        return empty($searchString) || strpos($text, $searchString) !== false;
    }
}