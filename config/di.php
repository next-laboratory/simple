<?php

return [
    'scanDir'  => [
        BASE_PATH . 'app/Http/Controllers',
        BASE_PATH . 'app/Listeners',
    ],
    // 依赖绑定
    'bindings' => [
        'Psr\Http\Server\RequestHandlerInterface' => 'App\Http\Kernel',
    ],
];
