<?php

namespace Bilfeldt\CorrelationId\Middleware;

use Bilfeldt\CorrelationId\CorrelationIdServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CorrelationIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldOverride($request) || ! $request->hasHeader(CorrelationIdServiceProvider::getCorrelationIdHeaderName())) {
            $request->headers->set(CorrelationIdServiceProvider::getCorrelationIdHeaderName(), $this->generateCorrelationId($request));
        }

        $response = $next($request);

        $response->headers->set(CorrelationIdServiceProvider::getCorrelationIdHeaderName(), $request->getCorrelationId());

        return $response;
    }

    protected function generateCorrelationId(Request $request): string
    {
        return Str::orderedUuid();
    }

    protected function shouldOverride(Request $request): bool
    {
        return config('correlation-id.correlation_override');
    }
}
