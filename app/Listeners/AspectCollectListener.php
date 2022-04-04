<?php

namespace App\Listeners;

class AspectCollectListener extends \Max\Di\Listeners\AspectCollectListener
{
    public function listen(): iterable
    {
        return [
            'Max\Foundation\Aspects\Cacheable',
        ];
    }
}
