<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Context for queue jobs
    |--------------------------------------------------------------------------
    |
    | This value determines if the correlation id, client request id and
    | request id should be added as global log context when a queued job is
    | being processed. You can disable this and instead implement the
    | LogContext job middleware for the those jobs that you want this for.
    |
    */

    'queue_context' => env('QUEUE_CONTEXT', true),

    /*
    |--------------------------------------------------------------------------
    | Correlation ID
    |--------------------------------------------------------------------------
    |
    | The Correlation ID should be defined at the edge of the application for
    | the greatest benefit. It can often be tricky to set the Correlation ID
    | at the edge of the application, so this package includes a middleware
    | to set the Correlation ID from the application. This values determines
    | whether or not any existing Correlation ID should be overridden.
    | This is per default enabled to ensure that the client does not pass
    | the Correlation ID to the application. If you assign the Correlation ID
    | at the edge of the application, you should disable this.
    |
    */

    'correlation_override' => env('CORRELATION_OVERRIDE', true),

    /*
    |--------------------------------------------------------------------------
    | Correlation ID header
    |--------------------------------------------------------------------------
    |
    | This value is the name of the request and response header containing
    | the Correlation ID.
    |
    */

    'correlation_id_header' => env('CORRELATION_ID_HEADER', 'Correlation-ID'),

    /*
    |--------------------------------------------------------------------------
    | Client Request ID header
    |--------------------------------------------------------------------------
    |
    | This value is the name of the request and response header containing
    | the Client Request ID.
    |
    */

    'client_request_id_header' => env('CLIENT_ID_REQUEST_HEADER', 'Request-ID'),
];
