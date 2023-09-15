<?php

namespace Bilfeldt\LaravelCorrelationId\Tests\Features;

use Bilfeldt\LaravelCorrelationId\Tests\TestCase;
use Illuminate\Http\Request;

class RequestMacroTest extends TestCase
{
    public function test_get_correlation_id_macro(): void
    {
        $request = new Request();

        $this->assertNull($request->getCorrelationId());

        $request->headers->set('Correlation-ID', 'test-correlation-id');

        $this->assertEquals('test-correlation-id', $request->getCorrelationId());
    }

    public function test_get_client_request_id_macro(): void
    {
        $request = new Request();

        $this->assertNull($request->getClientRequestId());

        $request->headers->set('Request-ID', 'test-request-id');

        $this->assertEquals('test-request-id', $request->getClientRequestId());
    }
}