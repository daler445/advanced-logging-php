<?php
    require_once('Debug.php');
    $debug = new Debug(true);
    $debug->setFilePath('file.log');
    $debug->log('test');
    $debug->outputLog();