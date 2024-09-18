<?php

namespace NycuCsit\LaravelRestfulUtils\Exceptions;

/**
 * An exception indicates that the request is refused to authorize it.
 *
 * Its instances can be serialized to an JSON response by
 * {@see \NycuCsit\LaravelRestfulUtils\Exceptions\Handler Handler}
 */
class ForbiddenException extends HttpApiException
{
    /**
     * Create a new Restful API exception for forbidden instance.
     *
     * @param string|null|\BackedEnum $code One of a server-defined set of error codes
     * @param string $message A human-readable representation of the error
     * @param array $more More data could be added into the 'error' field of the response object
     * @param array $meta More data could be appended to the response object
     */
    public function __construct($code = 'FORBIDDEN', string $message = '', array $more = [], array $meta = [])
    {
        parent::__construct(403, $code, $message, $more, $meta);
    }
}
