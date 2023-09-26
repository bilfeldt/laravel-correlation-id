<?php

namespace Bilfeldt\CorrelationId\Tests\Unit;

use Bilfeldt\CorrelationId\Middleware\LogContextMiddleware;
use Bilfeldt\CorrelationId\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LogContextMiddlewareTest extends TestCase
{
    #[Test]
    public function test_adds_request_correlation_id_to_log_context(): void
    {
        $id = 'test-correlation-id';

        $request = new Request();
        $request->headers->set('Correlation-ID', $id);
        $response = (new LogContextMiddleware())->handle($request, function ($request) use ($id) {
            $this->assertArrayHasKey('client_request_id', Log::sharedContext());
            $this->assertEquals($id, Log::sharedContext()['correlation_id']); // Assert context is set BEFORE the request is processed.

            return new Response();
        });

        $this->assertArrayHasKey('client_request_id', Log::sharedContext());
        $this->assertEquals($id, Log::sharedContext()['correlation_id']); // Assert context is set AFTER the request is processed.
    }

    #[Test]
    public function test_adds_client_request_id_to_log_context(): void
    {
        $id = 'test-request-id';

        $request = new Request();
        $request->headers->set('Request-ID', $id);
        $response = (new LogContextMiddleware())->handle($request, function ($request) use ($id) {
            $this->assertArrayHasKey('client_request_id', Log::sharedContext());
            $this->assertEquals($id, Log::sharedContext()['client_request_id']); // Assert context is set BEFORE the request is processed.

            return new Response();
        });

        $this->assertArrayHasKey('client_request_id', Log::sharedContext());
        $this->assertEquals($id, Log::sharedContext()['client_request_id']); // Assert context is set AFTER the request is processed.
    }
}