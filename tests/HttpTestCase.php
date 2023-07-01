<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class HttpTestCase extends TestCase
{
    protected Client $client;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = new Client();
    }

    public function __call($name, $arguments)
    {
        return $this->client->{$name}(...$arguments);
    }
}
