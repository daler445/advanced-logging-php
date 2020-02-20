<?php
class Debug
{
    private $pattern = "[{datetime}] [{level}] [{process}] [{thread}] - {log}\n";
    private $patternInit = "\n[{datetime}] [{thread}] - {log}\n";
    private $patternGroup = "[{level}] {log}\n";

    private $defaultFilePath = 'debug.log';
    private $filePath = '';
    private $useSystemLog = false;

    private $initDateTime;
    private $output;

    private $debugIsEnabled = true;
    private $isRelativeTime = false;
    private $printLogToScreen = false;
    private $groupLogging = false;

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

    public function log($message, $level = 'i') {
        $this->addToOutputLog($this->prepareDataDetailed($level,$message));
        if ($this->printLogToScreen === true) {
            print($message);
        }
    }
    public function error($message) {
        $this->addToOutputLog($this->prepareDataDetailed('error', $message));
        if ($this->printLogToScreen === true) {
            print($message);
        }
    }
    public function warn($message) {
        $this->addToOutputLog($this->prepareDataDetailed('warn', $message));
        if ($this->printLogToScreen === true) {
            print($message);
        }
    }
    public function notice($message) {
        $this->addToOutputLog($this->prepareDataDetailed('notice', $message));
        if ($this->printLogToScreen === true) {
            print($message);
        }
    }
    public function outputLog() {
        $this->writeToFile($this->output);
        $this->output = null;
    }
    public function setFilePath($path) {
        $this->filePath = $path;
    }

    public function enableWriteToLogFile() {
        $this->debugIsEnabled = true;
    }
    public function disableWriteToLogFile() {
        $this->debugIsEnabled = false;
    }

    public function setRelativeTime() {
        $this->isRelativeTime = true;
    }
    public function unsetRelativeTime() {
        $this->isRelativeTime = false;
    }

    public function enablePrintLogToScreen() {
        $this->printLogToScreen = true;
    }
    public function disablePrintLogToScreen() {
        $this->printLogToScreen = false;
    }

    public function enableGroupLogging() {
        $this->groupLogging = true;
    }
    public function disableGroupLogging() {
        $this->groupLogging = false;
    }

    public function useSystemLog() {
        $this->useSystemLog = true;
    }
    public function disuseSystemLog() {
        $this->useSystemLog = false;
    }

    private function setInitDateTime() {
        $this->initDateTime = $this->getTimeInMilliseconds();
    }
    private function getRelativeDate() {
        if ($this->initDateTime !== null) {
            return $this->generateRelativeTimeString($this->initDateTime, $this->getTimeInMilliseconds());
        }
        throw new DebugException('Cannot calculate relative time. Init date is not defined.');
    }
    private function getDateTime() {
        return date('d/m/Y:H:i:s O');
    }
    private function getTimeInMilliseconds() {
        $mt = explode(' ', microtime());
        return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
    }
    private function generateRelativeTimeString($time1, $time2) {
        // get milliseconds
        $time1_ms = substr($time1, -3);
        $time2_ms = substr($time2, -3);

        // get seconds
        $time1 = substr($time1, 0, -3);
        $time2 = substr($time2, 0, -3);

        $diff = $time2 - $time1;
        if ($diff < 0) {
            $diff *= -1;
        }

        $seconds = $diff;
        if ($seconds <= 60) {
            if($seconds === 0) {
                return ($time2_ms - $time1_ms) . ' ms ago';
            }
            if ($seconds === 1) {
                return '1 second ago';
            }
            return "$seconds seconds ago";
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

    private function getThread() {
        return basename(__FILE__);
    }
    private function getProcessId() {
        return (getmypid() !== false) ? getmygid() : null;
    }

    private function prepareDataDetailed($level = 'i', $data = '') {
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

        if ($this->groupLogging === true) {
            $temp = $this->patternGroup;
            $temp = $this->replaceByKey($temp, '{level}', $level);
            $temp = $this->replaceByKey($temp, '{log}', $data);
        } else {
            $temp = $this->pattern;
            $temp = $this->replaceByKey($temp, '{level}', $level);
            $temp = $this->replaceByKey($temp, '{log}', $data);
            $temp = $this->replaceByKey($temp, '{thread}', $this->getThread());
            $temp = $this->replaceByKey($temp, '{process}', ($this->getProcessId() === null) ? '' : $this->getProcessId());

            if ($this->isRelativeTime === false) {
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
        }
        return $temp;
    }
    private function replaceByKey($data, $key, $value) {
        return str_replace($key, $value, $data);
    }

    private function addToOutputLog($message) {
        if ($this->debugIsEnabled) {
            $this->output .= $message;
        }
    }
    protected function writeToFile($message){
        if ($this->useSystemLog) {
            error_log($message);
        } else {
            error_log($message,3,($this->filePath !== '' && $this->filePath !== null) ? $this->filePath : $this->defaultFilePath);
        }
    }
}