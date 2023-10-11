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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set(CorrelationIdServiceProvider::getClientRequestIdHeaderName(), $request->getClientRequestId());

        return $response;
    }
}
