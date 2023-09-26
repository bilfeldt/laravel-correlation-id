<?php

namespace Bilfeldt\LaravelCorrelationId\Tests;
use Bilfeldt\LaravelCorrelationId\CorrelationIdServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
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