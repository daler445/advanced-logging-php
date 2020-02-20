<?php

class DebugException extends Exception
{
    public function errorMessage()
    {
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile() . ': Cannot calculate relative time. Init date is not defined.';
        return $errorMsg;
    }
}