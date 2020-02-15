<?php

    require_once('Debug.php');
    $debug = new Debug(true);
    $debug->setFilePath('file.log');
    $debug->log('test 0');

    $debug->setRelativeTime();
    $debug->log('test 1');
    $debug->log('test 2');

    $debug->disableLog();
    $debug->enablePrintLogToScreen();

    $debug->log('test 3 - turned off but printing');
    usleep(3000);

    $debug->enableLog();
    $debug->disablePrintLogToScreen();
    $debug->log('test 4');
    $debug->outputLog();