<?php
class Debug
{
    private $startTime;
    private $pattern = "[{datetime}] [{type}] [{process}] – {thread} {log}";
    private $logFile = "debug.log";

    private function prepareData($type = "", $data = "", $timeAbs = "")
    {
        $temp = $this->pattern;

        switch ($type) {
            case "d":
            case "debug":
                $type = "DEBUG";
                break;
            case "e":
            case "error":
                $type = "ERROR";
                break;
            case "w":
            case "warn":
            case "warning":
                $type = "WARN";
                break;
            case "n":
            case "notice":
                $type = "NOTICE";
                break;
            case "i":
            case "info":
            default:
                $type = "INFO";
                break;
        }
        $temp = replaceByKey($temp, "{type}", $type);
    }

    private function writeToFile()
    {

    }

    private function replaceByKey($data, $key, $value) {
        return str_replace($key, $value, $data);
    }
}