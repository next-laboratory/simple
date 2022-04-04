<?php

namespace App\Events;

class TestEvent
{
    public function __construct(public string $name)
    {
    }
}