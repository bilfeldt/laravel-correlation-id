<?php

namespace Bilfeldt\LaravelCorrelationId\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CorrelationIdMiddleware
{
    private const HEADER_NAME = 'Correlation-ID';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set(self::HEADER_NAME, $this->generateCorrelationId($request));

        return $next($request)->header(self::HEADER_NAME, $request->headers->get(self::HEADER_NAME));
    }

    protected function generateCorrelationId(Request $request): string
    {
        return Str::orderedUuid();
    }
}