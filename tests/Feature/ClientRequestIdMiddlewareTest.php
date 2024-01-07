<?php

namespace Bilfeldt\CorrelationId\Tests\Feature;

use Bilfeldt\CorrelationId\Middleware\ClientRequestIdMiddleware;
use Bilfeldt\CorrelationId\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ClientRequestIdMiddlewareTest extends TestCase
{
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

    public function test_works_with_binary_file_response(): void
    {
        $uuid = Str::orderedUuid();

        $request = new Request();
        $request->headers->set('Request-ID', $uuid);
        $response = (new ClientRequestIdMiddleware())->handle($request, function ($request) {
            return new BinaryFileResponse(__DIR__.'/../fixtures/dummy.pdf');
        });

        $this->assertTrue($response->headers->has('Request-ID'));
        $this->assertEquals($uuid, $response->headers->get('Request-ID'));
    }
}
