<?php

namespace Bilfeldt\LaravelCorrelationId;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class LaravelCorrelationIdServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        Request::macro('getCorrelationId', function (): ?string {
            return $this->header('Correlation-ID');
        });

        Request::macro('getClientRequestId', function (): ?string {
            return $this->header('Request-ID');
        });
    }
}
