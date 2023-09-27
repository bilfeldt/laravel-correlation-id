<?php

namespace Bilfeldt\CorrelationId\Jobs\Middleware;

use Bilfeldt\CorrelationId\CorrelationIdServiceProvider;
use Closure;
use Illuminate\Support\Facades\Log;

class LogContext
{
    /**
     * Process the queued job.
     *
     * @param  \Closure(object): void  $next
     */
    public function handle(object $job, Closure $next): void
    {
        Log::shareContext([
            'correlation_id' => $job->payload()['data'][CorrelationIdServiceProvider::PAYLOAD_KEY_CORRELATION_ID] ?? null,
            'request_id' => request()->getUniqueId(),
        ]);

        $next($job);
    }
}
