<?php
class Debug
{
    private $pattern = "[{datetime}] [{level}] [{process}] [{thread}] - {log}\n";
    private $patternInit = "[{datetime}] [{thread}] - {log}\n";
    private $defaultFilePath = 'debug.log';
    private $filePath = '';
    private $initDateTime;

    private $output;

    public function __construct($initMessage = false) {
        $this->setInitDateTime();

        if ($initMessage === true) {
            $temp = $this->patternInit;

            $temp = $this->replaceByKey($temp, '{datetime}', $this->getDateTime());
            $temp = $this->replaceByKey($temp, '{thread}', $this->getThread());
            $temp = $this->replaceByKey($temp, '{log}', 'Log initialized');

            $this->addToOutputLog($temp);
        }
    }

    public function log($message) {
        $this->addToOutputLog($this->prepareDataDetailed('info',$message));
    }
    public function outputLog() {
        $this->writeToFile($this->output);
        $this->output = null;
    }
    public function setFilePath($path) {
        $this->filePath = $path;
    }

    private function setInitDateTime() {
        $this->initDateTime = time();
    }
    private function getDateTime() {
        return date('d/m/Y:H:i:s O');
    }
    private function getThread() {
        return basename(__FILE__);
    }
    private function getProcessId() {
        return (getmypid() !== false) ? getmygid() : null;
    }
    private function getRelativeDate() {
        if ($this->initDateTime !== null) {
            $diff = time() - $this->initDateTime;
            return $this->generateRelativeTimeString($diff);
        }
        throw new DebugException('Cannot calculate relative time. Init date is not defined.');
    }
    private function generateRelativeTimeString($diff) {
        if ($diff < 1) {
            return '0 seconds';
        }

        $seconds = $diff;
        if ($seconds <= 60) {
            return "$seconds ago";
        }

        $minutes = round($diff / 60);
        if ($minutes <= 60) {
            if($minutes === 1){
                return 'one minute ago';
            }
            return "$minutes minutes ago";
        }

        $hours = round($diff / 3600);
        if($hours <= 24){
            if($hours === 1) {
                return 'an hour ago';
            }
            return "$hours hrs ago";
        }

        $days = round($diff / 86400);
        if ($days <= 7) {
            if ($days === 1) {
                return '1 day ago';
            }
            return "$days ago";
        }

        $weeks = round($diff / 604800);
        if ($weeks <= 4.3) {
            if ($weeks === 1) {
                return '1 week ago';
            }
            return "$weeks weeks ago";
        }

        $months = round($diff / 2600640);
        if ($months <= 12) {
            if ($months === 1) {
                return '1 month ago';
            }
            return "$months months ago";
        }

        $years = round($diff / 31207680);
        if ($years === 1) {
            return 'One year ago';
        }
        return "$years years ago";
    }
    private function prepareDataDetailed($level = '', $data = '', $timeAbs = true)
    {
        $temp = $this->pattern;

        switch (strtolower($level)) {
            case 'd':
            case 'debug':
                $level = 'DEBUG';
                break;
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
        $temp = $this->replaceByKey($temp, '{level}', $level);
        $temp = $this->replaceByKey($temp, '{log}', $data);
        $temp = $this->replaceByKey($temp, '{thread}', $this->getThread());
        $temp = $this->replaceByKey($temp, '{process}', ($this->getProcessId() === null) ? '' : $this->getProcessId());

        if ($timeAbs === true) {
            $temp = $this->replaceByKey($temp, '{datetime}', $this->getDateTime());
        } else {
            try {
                $temp = $this->replaceByKey($temp, '{datetime}', $this->getRelativeDate());
            } catch (DebugException $e) {
                $this->setInitDateTime();
                try {
                    $temp = $this->replaceByKey($temp, '{datetime}', $this->getRelativeDate());
                } catch (DebugException $e) {
                    $temp = $this->replaceByKey($temp, '{datetime}', '[Relative time error]');
                }
            }
        }

        return $temp;
    }
    private function addToOutputLog($message) {
        $this->output .= $message;
    }
    private function writeToFile($message){
        error_log($message,3,($this->filePath !== '' && $this->filePath !== null) ? $this->filePath : $this->defaultFilePath);
    }
    private function replaceByKey($data, $key, $value) {
        return str_replace($key, $value, $data);
    }
}