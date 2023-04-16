<?php

error_reporting(E_ALL);
ini_set('display_errors', 'Off');

const ROOT_DIR = __DIR__;

// write error log file
ini_set('log_errors', 'On');
ini_set('error_log', __DIR__ . '/php_error.log');

/**
 * Load minimal required files
 */
require_once __DIR__ . '/core/Autoload.php';
require_once __DIR__ . '/core/helpers.php';
