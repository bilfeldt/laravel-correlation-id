<?php

namespace Bilfeldt\CorrelationId\Tests;

use Bilfeldt\CorrelationId\CorrelationIdServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            CorrelationIdServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        //
    }
}
