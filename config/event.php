<?php

return [
    'listeners' => [
        \Max\Foundation\Listeners\ListenerCollectListener::class,
        \App\Listeners\AspectCollectListener::class,
        \Max\Foundation\Listeners\RouteCollectListener::class,
    ],
];
