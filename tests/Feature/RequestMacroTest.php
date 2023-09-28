<?php

namespace Bilfeldt\CorrelationId\Tests\Feature;

use Bilfeldt\CorrelationId\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RequestMacroTest extends TestCase
{
    public function test_request_macro_get_correlation_id_is_registered()
    {
        $this->assertTrue((new Request())->hasMacro('getCorrelationId'));
    }

    public function test_get_correlation_id_macro(): void
    {
        $request = new Request();

        $this->assertNull($request->getCorrelationId());

        $request->headers->set('Correlation-ID', 'test-correlation-id');

        $this->assertEquals('test-correlation-id', $request->getCorrelationId());
    }

    public function test_correlation_id_is_sanitize(): void
    {
        $request = new Request();
        $request->headers->set('Correlation-ID', 'this-SHOULD-be-<sanitized>% !-in-a-good-123-implementation');

        $this->assertEquals('this-SHOULD-be-sanitized-in-a-good-123-implementation', $request->getCorrelationId());
    }

    public function test_request_macro_get_client_request_id_is_registered()
    {
        $this->assertTrue((new Request())->hasMacro('getClientRequestId'));
    }

    public function test_get_client_request_id_macro(): void
    {
        $request = new Request();

        $this->assertNull($request->getClientRequestId());

        $request->headers->set('Request-ID', 'test-request-id');

        $this->assertEquals('test-request-id', $request->getClientRequestId());
    }

    public function test_client_request_id_is_sanitize(): void
    {
        $request = new Request();
        $request->headers->set('Request-ID', 'this-SHOULD-be-<sanitized>% !-in-a-good-123-implementation');

        $this->assertEquals('this-SHOULD-be-sanitized-in-a-good-123-implementation', $request->getClientRequestId());
    }

    public function test_request_macro_get_unique_id_is_registered()
    {
        $this->assertTrue((new Request())->hasMacro('getUniqueId'));
    }

    public function test_request_macro_get_unique_id()
    {
        $request = new Request();

        $uuid = $request->getUniqueId();

        $this->assertTrue(Str::isUuid($uuid));
        $this->assertEquals($uuid, $request->getUniqueId());
        $this->assertEquals($uuid, $request->getUniqueId()); // Assert calling the function twice returns the same value
    }
}
