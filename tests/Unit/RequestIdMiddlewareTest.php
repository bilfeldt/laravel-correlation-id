<?php

namespace Bilfeldt\LaravelCorrelationId\Tests\Unit;

use Bilfeldt\LaravelCorrelationId\Middleware\RequestIdMiddleware;
use Bilfeldt\LaravelCorrelationId\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class RequestIdMiddlewareTest extends TestCase
{
    #[Test]
    public function test_adds_request_id_to_response_header(): void
    {
        $uuid = Str::orderedUuid();

        $request = new Request();
        $request->headers->set('Request-ID', $uuid);
        $response = (new RequestIdMiddleware())->handle($request, function ($request) {
            return new Response();
        });

        $this->assertTrue($response->headers->has('Request-ID'));
        $this->assertEquals($uuid, $response->headers->get('Request-ID'));
    }
}