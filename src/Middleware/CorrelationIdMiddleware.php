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
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldOverride($request) || ! $request->hasHeader(CorrelationIdServiceProvider::getCorrelationIdHeaderName())) {
            $request->headers->set(CorrelationIdServiceProvider::getCorrelationIdHeaderName(), $this->generateCorrelationId($request));
        }

        return $next($request)->header(CorrelationIdServiceProvider::getCorrelationIdHeaderName(), $request->getCorrelationId());
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
