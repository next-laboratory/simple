<?php

namespace App\Http\Middleware;

use Max\Http\Server\Middleware\SessionMiddleware as CoreSessionMiddleware;

class SessionMiddleware extends CoreSessionMiddleware
{
    protected bool $secure = false;
}
