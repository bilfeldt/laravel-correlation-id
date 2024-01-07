<?php

namespace Bilfeldt\CorrelationId\Tests\Feature;

use Bilfeldt\CorrelationId\Middleware\LogContextMiddleware;
use Bilfeldt\CorrelationId\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LogContextMiddlewareTest extends TestCase
{
    public function test_adds_request_correlation_id_to_log_context(): void
    {
        $id = 'test-correlation-id';

        $request = new Request();
        $request->headers->set('Correlation-ID', $id);
        $response = (new LogContextMiddleware())->handle($request, function ($request) use ($id) {
            $this->assertArrayHasKey('correlation_id', Log::sharedContext());
            $this->assertEquals($id, Log::sharedContext()['correlation_id']); // Assert context is set BEFORE the request is processed.

            return new Response();
        });

        $this->assertArrayHasKey('correlation_id', Log::sharedContext());
        $this->assertEquals($id, Log::sharedContext()['correlation_id']); // Assert context is set AFTER the request is processed.
    }

    public function test_adds_request_id_to_log_context(): void
    {
        $request = new Request();
        $response = (new LogContextMiddleware())->handle($request, function ($request) {
            return new Response();
        });

        $this->assertArrayHasKey('request_id', Log::sharedContext());
        $this->assertEquals($request->getUniqueId(), Log::sharedContext()['request_id']); // Assert context is set AFTER the request is processed.
    }
}
