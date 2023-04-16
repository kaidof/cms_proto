<?php

return [
    // starting web path for application (example: /app -> http://localhost/app)
    'web_root' => '',

    // modules that are used in the project
    'modules' => [
        'news' => [
            'init' => \modules\news\NewsInit::class,
            'enabled' => true,
        ],

        // duplicate modules for testing
        'news2' => [
            'init' => \modules\news2\NewsInit::class,
            'enabled' => true,
        ],
        'news3' => [
            'init' => \modules\news3\NewsInit::class,
            'enabled' => true,
        ],
    ],
];
