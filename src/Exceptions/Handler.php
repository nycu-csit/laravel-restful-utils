<?php

namespace NycuCsit\LaravelRestfulUtils\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Route;
use Illuminate\Validation\ValidationException;
use Throwable;

/**
 * Exception handler
 *
 * You may use this as base class for your \App\Exceptions\Handler to get error wrapping feature.
 */
class Handler extends ExceptionHandler
{
    /**
     * Determine if the exception handler response should be JSON.
     *
     * Typically, the API endpoint should respond JSON.
     * Override shouldReturnJson() to prevent responding a webpage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldReturnJson($request, Throwable $e)
    {
        $route = $request->route();
        $middlewareGroup = $route instanceof Route ? $route->gatherMiddleware() : [];

        return parent::shouldReturnJson($request, $e) || in_array('api', $middlewareGroup);
    }

    protected function convertExceptionToArray(Throwable $e): array
    {
        return [
            'error' => array_merge([
                'code' => config('app.debug') ? $e->getCode() : 'SERVER_ERROR',
                'message' => 'Server Error'
            ], parent::convertExceptionToArray($e)),
        ];
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $response = parent::unauthenticated($request, $exception);
        if ($response instanceof JsonResponse) {
            return $this->overrideJsonResponse($response, 'UNAUTHORIZED');
        }
        return $response;
    }

    private function overrideJsonResponse(JsonResponse $response, string $code): JsonResponse
    {
        /** @var array $data */
        $data = $response->getData(true);
        $response->setData([
            'error' => array_merge([
                'code' => $code,
                'message' => 'Validation failed'
            ], $data),
        ]);
        return $response;
    }

    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return $this->overrideJsonResponse(parent::invalidJson($request, $exception), 'VALIDATION_FAILED');
    }
}
