<?php

    require_once('Debug.php');
    $debug = new Debug(true);
    $debug->setFilePath('file.log');
    $debug->log('test 0');

    $debug->setRelativeTime();
    $debug->log('test 1');
    $debug->log('test 2');

    $debug->disableWriteToLogFile();
    $debug->enablePrintLogToScreen();

    $debug->log('test 3 - turned off but printing');
    usleep(3000);

    $debug->enableWriteToLogFile();
    $debug->disablePrintLogToScreen();
    $debug->log('test 4');

    $debug->enableGroupLogging();
    $debug->log('test 5 - group logging');
    $debug->log('test 6');
    $debug->log('test 7');
    $debug->disableGroupLogging();
    $debug->log('test 8');


    $debug->outputLog();