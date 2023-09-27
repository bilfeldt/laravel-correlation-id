<?php

namespace Bilfeldt\CorrelationId;

use Illuminate\Http\Request;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class CorrelationIdServiceProvider extends ServiceProvider
{
    protected const PAYLOAD_KEY_CORRELATION_ID = 'correlation_id';
    protected const PAYLOAD_KEY_CLIENT_REQUEST_ID = 'client_request_id';

    public static function getClientRequestIdHeaderName(): string
    {
        return config('correlation-id.client_request_id_header');
    }

    public static function getCorrelationIdHeaderName(): string
    {
        return config('correlation-id.correlation_id_header');
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('correlation-id.php'),
            ], 'config');
        }

        $this->bootQueueCallbacks();

        $this->bootRequestGetUniqueIdMacro();
        $this->bootRequestGetCorrelationIdMacro();
        $this->bootRequestGetClientRequestIdMacro();
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'correlation-id');
    }

    protected function bootQueueCallbacks(): void
    {
        // Inspired by: https://james.brooks.page/blog/injecting-additional-data-into-laravel-queued-jobs
        Queue::createPayloadUsing(function ($connection, $queue, $payload) {
            if (! isset($payload['data'][self::PAYLOAD_KEY_CORRELATION_ID])) {
                $payload['data'][self::PAYLOAD_KEY_CORRELATION_ID] = request()->getCorrelationId();
            }

            if (! isset($payload['data'][self::PAYLOAD_KEY_CLIENT_REQUEST_ID])) {
                $payload['data'][self::PAYLOAD_KEY_CLIENT_REQUEST_ID] = request()->getClientRequestId();
            }

            return $payload;
        });

        Queue::before(function (JobProcessing $event) {
            $request = request();

            // Adding it to the request, so that any subsequent jobs that are created will also get these attached
            if (! $request->header(self::getCorrelationIdHeaderName())) {
                $request->headers->set(self::getCorrelationIdHeaderName(), $event->job->payload()['data'][self::PAYLOAD_KEY_CORRELATION_ID] ?? null);
            }

            if (! $request->header(self::getClientRequestIdHeaderName())) {
                $request->headers->set(self::getClientRequestIdHeaderName(), $event->job->payload()['data'][self::PAYLOAD_KEY_CLIENT_REQUEST_ID] ?? null);
            }

            if (config('correlation-id.queue_context')) {
                // Question, can we do this via the middleware instead?
                Log::shareContext([
                    'correlation_id' => $event->job->payload()['data'][self::PAYLOAD_KEY_CORRELATION_ID] ?? null,
                    'client_request_id' => $event->job->payload()['data'][self::PAYLOAD_KEY_CLIENT_REQUEST_ID] ?? null,
                    'request_id' => $request->getUniqueId(),
                ]);
            }
        });
    }

    protected function bootRequestGetUniqueIdMacro(): void
    {
        if (! Request::hasMacro('getUniqueId')) {
            Request::macro('getUniqueId', function (): string {
                if (! $this->attributes->has('uuid')) {
                    $this->attributes->set('uuid', (string) Str::orderedUuid());
                }

                return $this->attributes->get('uuid');
            });
        } else {
            Log::warning('Request::getUniqueId() already exists, skipping macro registration.');
        }
    }

    protected function bootRequestGetCorrelationIdMacro(): void
    {
        if (! Request::hasMacro('getCorrelationId')) {
            Request::macro('getCorrelationId', function (): ?string {
                return $this->header(CorrelationIdServiceProvider::getCorrelationIdHeaderName());
            });
        } else {
            Log::warning('Request::getCorrelationId() already exists, skipping macro registration.');
        }
    }

    protected function bootRequestGetClientRequestIdMacro(): void
    {
        if (! Request::hasMacro('getClientRequestId')) {
            Request::macro('getClientRequestId', function (): ?string {
                return $this->header(CorrelationIdServiceProvider::getClientRequestIdHeaderName());
            });
        } else {
            Log::warning('Request::getClientRequestId() already exists, skipping macro registration.');
        }
    }
}
