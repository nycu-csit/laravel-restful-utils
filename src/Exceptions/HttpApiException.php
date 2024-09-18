<?php

namespace NycuCsit\LaravelRestfulUtils\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InvalidArgumentException;
use RuntimeException;

/**
 * An HTTP API Exception, its instances can be serialized to an JSON response by
 * {@see \NycuCsit\LaravelRestfulUtils\Exceptions\Handler Handler}
 */
class HttpApiException extends RuntimeException
{
    public int $status;
    public string $errorCode;
    public array $more;
    public array $meta;

    /**
     * Create a new Restful API exception instance.
     *
     * @param int $status HTTP status code
     * @param string|null|\BackedEnum $code One of a server-defined set of error codes. If $code is null, $code is set
     *                                      to corresponding HTTP status text of $status.
     * @param string $message A human-readable representation of the error
     * @param array $more More data could be added into the 'error' field of the response object
     * @param array $meta More data could be appended to the response object
     */
    public function __construct(
        int $status,
        $code = null,
        string $message = '',
        array $more = [],
        array $meta = []
    ) {
        parent::__construct($message, 1);
        $this->errorCode = $this->initializeCode($code) ?? $this->getStatusText($status);
        $this->status = $status;
        $this->more = $more;
        $this->meta = $meta;
    }

    /**
     * @param mixed $code
     * @return string|null
     */
    private function initializeCode($code): ?string
    {
        if (is_null($code)) {
            return null;
        } elseif (is_string($code)) {
            return $code;
        } elseif (
            is_object($code) &&
            is_a($code, '\BackedEnum') &&
            property_exists($code, 'value') &&
            is_string($code->value)
        ) {
            // $code is a BackedEnum
            return $code->value;
        }

        throw new InvalidArgumentException('$code of HttpApiException must be a string, \BackedEnum, or null.');
    }

    private function getStatusText(int $status): string
    {
        $unknownStatus = 'UNKNOWN_STATUS';

        // Format 'Method Not Allowed' to 'METHOD_NOT_ALLOWED'
        return preg_replace(
            '/_+/',
            '_',
            preg_replace(
                "/[^A-Za-z\d]/",
                '_',
                strtoupper(Response::$statusTexts[$status] ?? $unknownStatus)
            ) ?? $unknownStatus
        ) ?? $unknownStatus;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param Request $request
     * @return Response
     * @noinspection PhpUnusedParameterInspection
     */
    public function render(Request $request): Response
    {
        $error = [
            'code' => $this->errorCode,
            'message' => $this->message
        ];
        $error = array_merge($error, $this->more);
        $content = [
            'error' => $error
        ];
        $content = array_merge($content, $this->meta);
        return new Response($content, $this->status);
    }

    public function context(): array
    {
        return [
            'statusCode' => $this->status,
            'errorCode' => $this->errorCode,
            'more' => $this->more,
            'meta' => $this->meta,
        ];
    }
}
