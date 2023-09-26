<?php

namespace Bilfeldt\LaravelCorrelationId\Middleware;

use Bilfeldt\LaravelCorrelationId\LaravelCorrelationIdServiceProvider;
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
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldOverride($request) || ! $request->hasHeader(LaravelCorrelationIdServiceProvider::getCorrelationIdHeaderName())) {
            $request->headers->set(LaravelCorrelationIdServiceProvider::getCorrelationIdHeaderName(), $this->generateCorrelationId($request));
        }

        return $next($request)->header(LaravelCorrelationIdServiceProvider::getCorrelationIdHeaderName(), $request->getCorrelationId());
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