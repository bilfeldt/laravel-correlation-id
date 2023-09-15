<?php

namespace Bilfeldt\LaravelCorrelationId;

use Illuminate\Http\Request;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class LaravelCorrelationIdServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Inspired by: https://james.brooks.page/blog/injecting-additional-data-into-laravel-queued-jobs
        Queue::createPayloadUsing(function ($connection, $queue, $payload) {
            if (! isset($payload['data']['correlation_id'])) {
                $payload['data']['correlation_id'] = request()->getCorrelationId();
            }

            if (! isset($payload['data']['client_request_id'])) {
                $payload['data']['client_request_id'] = request()->getClienRequestId();
            }

            return $payload;
        });

        Queue::before(function (JobProcessing $event) {
            $request = request();

            // Adding it to the request, so that any subsequent jobs that are created will also get these attached
            if (! $request->header('Correlation-ID')) {
                $request->headers->set('Correlation-ID', $event->job->payload()['data']['correlation_id'] ?? null);
            }

            if (! $request->header('Request-ID')) {
                $request->headers->set('Request-ID', $event->job->payload()['data']['client_request_id'] ?? null);
            }

            Log::shareContext([
                'correlation_id' => $event->job->payload()['data']['correlation_id'] ?? null,
                'client_request_id' => $event->job->payload()['data']['client_request_id'] ?? null,
            ]);
        });
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
