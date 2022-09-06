<?php

namespace Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

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
