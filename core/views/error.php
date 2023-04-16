<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error</title>
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            color: #333;
            width: 100vw;
            min-height: 100vh;
            display: grid;
            grid-template-rows: auto 1fr auto;
            grid-template-columns: 1fr;
            grid-template-areas:
                "header"
                "main"
                "footer";
        }

        header {
            background: #f00;
            color: #fff;
            padding: 1rem;
            font-weight: bold;
            font-size: 2rem;
        }

        main {
            padding: 1rem;
            grid-area: main;
            display: flex;
            flex-direction: column;
            width: 100vw;
        }

        footer {
            background-color: #293338;
            color: #98a5ab;
            padding: 1rem;
            font-size: 1.5rem;
            grid-area: footer;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        footer > div {
            display: flex;
            flex-direction: row;
            flex: 1 0 0
        }

        footer > div > span {
            gap: 0.5rem;
            flex: 0 0 280px;
        }

        pre {
            background: #eee;
            margin: 0;
            flex-shrink: 0;
            flex-grow: 1;
        }

        .code {
            background: #eee;
            padding: 1.2rem;
            margin: 0 0 1rem;
            overflow-x: auto;
            display: flex;
        }

        h1 {
            font-size: 24px;
            color: #f00;
        }
    </style>
</head>
<body>
    <header>Application Error</header>
    <main>
        <h1>Error</h1>
        <div class="code"><pre><?= $error; ?></pre></div>
        <h1>Trace</h1>
        <div class="code"><pre><?= $trace; ?></pre></div>
    </main>
    <footer>
<?php

$performance = [
    'memory' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
    'memory_peak' => round(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
    'execution_time_human' => sprintf('%.4f', microtime(true) - constant('PAGE_START_TIME')) . ' sec',
];

?>

        <div>
            <span>Memory usage</span>
            <span><?= $performance['memory'] ?></span>
        </div>
        <div>
            <span>Memory peak usage</span>
            <span><?= $performance['memory_peak'] ?></span>
        </div>
        <div>
            <span>Execution time</span>
            <span><?= $performance['execution_time_human'] ?></span>
        </div>

    </footer>
</body>
</html>
