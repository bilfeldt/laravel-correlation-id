<?php

namespace Bilfeldt\CorrelationId\Tests\Unit;

use Bilfeldt\CorrelationId\Middleware\CorrelationIdMiddleware;
use Bilfeldt\CorrelationId\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CorrelationIdMiddlewareTest extends TestCase
{
    #[Test]
    public function test_adds_request_correlation_id_header(): void
    {
        $uuid = Str::orderedUuid();
        Str::createUuidsUsing(fn () => $uuid); // It is not possible to use mock, sine Str uses static methods.

        $request = new Request();
        $response = (new CorrelationIdMiddleware())->handle($request, function ($request) {
            return new Response();
        });

        $this->assertTrue($request->headers->has('Correlation-ID'));
        $this->assertEquals($uuid, $request->headers->get('Correlation-ID'));
    }

    #[Test]
    public function test_adds_response_correlation_id_header(): void
    {
        $request = new Request();
        $response = (new CorrelationIdMiddleware())->handle($request, function ($request) {
            return new Response();
        });

        $this->assertTrue($response->headers->has('Correlation-ID'));
        $this->assertEquals($response->headers->get('Correlation-ID'), $request->header('Correlation-ID'));
    }
}