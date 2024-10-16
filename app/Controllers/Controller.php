<?php

namespace App\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(version: '0.1', title: 'NextPHP')]
#[OA\SecurityScheme(securityScheme: 'api', type: 'http', bearerFormat: 'JWT', scheme: 'bearer')]
abstract class Controller
{
}
