<?php

declare(strict_types=1);

define('PAGE_START_TIME', microtime(true));

session_start();

try {
    require_once __DIR__ . '/core.php';
    require_once __DIR__ . '/modules.php';

    // process request
    \core\router\Router::dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (\Throwable $e) {
    // echo $e->getMessage();
    logger()->error('INDEX ERROR: ' . $e->getMessage() . ' ' . $e->getTraceAsString());

    if (!app()->environment('production')) {
        echo $e->getMessage();
        echo '<pre>';
        echo $e->getTraceAsString();
        echo '</pre>';
    } else {
        echo 'Something went wrong';

        // show error 500 page
        http_response_code(500);
    }
}





// get php execution time
$executionTime = microtime(true) - constant('PAGE_START_TIME');
$performance = [
    'memory' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
    'memory_peak' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
    'execution_time' => $executionTime,
    'execution_time_human' => sprintf('%.4f', $executionTime) . ' sec',
];

/*
echo "<!--\n";
print_r($performance);
echo "\n-->";
*/
