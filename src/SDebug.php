<?php

class SDebug
{
    public static function log($message, $level, $file = null)
    {
        $log_data = (new self)->prepareDataDetailed($level, $message);
        if ($file === null) {
            (new self)->writeToFile($log_data);
        } else {
            (new self)->writeToFile($log_data, $file);
        }
    }

    public static function notice($message, $file = null)
    {
        $log_data = (new self)->prepareDataDetailed('n', $message);
        if ($file === null) {
            (new self)->writeToFile($log_data);
        } else {
            (new self)->writeToFile($log_data, $file);
        }
    }

    public static function warning($message, $file = null)
    {
        $log_data = (new self)->prepareDataDetailed('w', $message);
        if ($file === null) {
            (new self)->writeToFile($log_data);
        } else {
            (new self)->writeToFile($log_data, $file);
        }
    }

    public static function info($message, $file = null)
    {
        $log_data = (new self)->prepareDataDetailed('i', $message);
        if ($file === null) {
            (new self)->writeToFile($log_data);
        } else {
            (new self)->writeToFile($log_data, $file);
        }
    }

    public static function error($message, $file = null)
    {
        $log_data = (new self)->prepareDataDetailed('e', $message);
        if ($file === null) {
            (new self)->writeToFile($log_data);
        } else {
            (new self)->writeToFile($log_data, $file);
        }
    }

    private function prepareDataDetailed($level = 'i', $data = '')
    {
        switch (strtolower($level)) {
            case 'e':
            case 'error':
                $level = 'ERROR';
                break;
            case 'w':
            case 'warn':
            case 'warning':
                $level = 'WARN';
                break;
            case 'n':
            case 'notice':
                $level = 'NOTICE';
                break;
            case 'i':
            case 'info':
            default:
                $level = 'INFO';
                break;
        }

        $datetime = date('d/m/Y:H:i:s O');
        $referer = basename(__FILE__);

        return "[$datetime] [$level] [$referer] – $data\n";
    }

    private function writeToFile($message, $file = null)
    {
        error_log($message, 3, ($file !== null) ? $file : 'debug.log');
    }
}