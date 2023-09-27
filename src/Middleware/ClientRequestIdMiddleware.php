<?php

namespace Bilfeldt\CorrelationId\Middleware;

use Bilfeldt\CorrelationId\CorrelationIdServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientRequestIdMiddleware
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
        return $next($request)->header(CorrelationIdServiceProvider::getClientRequestIdHeaderName(), $request->getClientRequestId());
    }
}
