<?php

namespace Bilfeldt\CorrelationId\Tests\Unit;

use Bilfeldt\CorrelationId\Middleware\ClientRequestIdMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class RequestIdMiddlewareTest extends TestCase
{
    #[Test]
    public function test_adds_request_id_to_response_header(): void
    {
        $uuid = Str::orderedUuid();

        $request = new Request();
        $request->headers->set('Request-ID', $uuid);
        $response = (new ClientRequestIdMiddleware())->handle($request, function ($request) {
            return new Response();
        });

        $this->assertTrue($response->headers->has('Request-ID'));
        $this->assertEquals($uuid, $response->headers->get('Request-ID'));
    }
}