<?php
/**
 * Static method
 */
require_once('../src/Debug.php');

// create object
$debug = new Debug(true);

// set path
$debug->setFilePath('from_source_static.log');

$debug->log('Initialize');

// set relative time
$debug->setRelativeTime();

// relative time test
$debug->log('Relative time 1');
usleep(1500);
$debug->log('Relative time 2');

$debug->unsetRelativeTime();

// disable writing to file
$debug->disableWriteToLogFile();

$debug->log('This will be skipped');

// enable printing logs
$debug->enablePrintLogToScreen();

$debug->log('This will be only printed');

// enable writing to file
$debug->enableWriteToLogFile();

// disable printing
$debug->disablePrintLogToScreen();

$debug->log('Back to normal');

// start group logging
$debug->enableGroupLogging();

$debug->log('test 1 - group logging');
$debug->log('test 2');
$debug->log('test 3 - group logging end');

// stop group logging
$debug->disableGroupLogging();

$debug->log('Back to normal');

// write logs to file
$debug->outputLog();

/**
 * Non static method
 */
require_once('../src/SDebug.php');
SDebug::info('Info message');
SDebug::error('Error message');
SDebug::notice('Notice message');
SDebug::warning('Warning message');