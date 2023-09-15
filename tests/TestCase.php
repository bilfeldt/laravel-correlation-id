<?php

namespace Bilfeldt\LaravelCorrelationId\Tests;
use Bilfeldt\LaravelCorrelationId\LaravelCorrelationIdServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{

    protected function getPackageProviders($app)
    {
        return [
            LaravelCorrelationIdServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        //
    }
}