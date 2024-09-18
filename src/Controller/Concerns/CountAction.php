<?php

namespace NycuCsit\LaravelRestfulUtils\Controller\Concerns;

use Illuminate\Http\Request;

/**
 * @mixin Context
 * @mixin IndexAction
 */
trait CountAction
{
    /**
     * Count the API resources.
     *
     * Corresponding API may be `GET /api/resources/count`.  This is not a Laravel-defined API.
     *
     * It is not recommended to override this method, you may write a new method 'count' with the parameters you want
     * and call this method.
     *
     * Example:
     * ```php
     * public function count(Request $request)
     * {
     *     return parent::countAction($request);
     * }
     * ```
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|array|mixed|null
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function countAction(Request $request)
    {
        $this->request = $request;

        $this->validateCountRequest();
        $this->authorizeCountRequest();
        $this->setupQueryBuilder();
        $this->buildCountQuery();
        $this->executeCountQuery();
        $this->postprocessCountResult();

        return $this->result;
    }

    /**
     * Validate the count request.
     *
     * You may override {@see indexRequestValidationRule()} to provide validation rules.
     *
     * @throws \Illuminate\Validation\ValidationException if the validation failed
     */
    protected function validateCountRequest(): void
    {
        $this->validateIndexRequest();
    }

    /**
     * Authorize the count request
     * {@link https://laravel.com/docs/master/authorization#via-controller-helpers via controller helpers}.
     *
     * You must create a {@link https://laravel.com/docs/master/authorization#creating-policies policy} for this method.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function authorizeCountRequest(): void
    {
        $this->authorizeIndexRequest();
    }

    /**
     * Build count query by request or other criteria.
     */
    public function buildCountQuery(): void
    {
        $this->query = $this->buildIndexQuery();
    }

    /**
     * Execute the query in $this->query and assign the query result to $this->result.
     */
    protected function executeCountQuery(): void
    {
        $this->result = $this->query->count();
    }

    /**
     * Postprocess the query result ($this->result) after the query is executed.
     */
    protected function postprocessCountResult(): void
    {
        $this->result = [
            'data' => null,
            'total' => $this->result
        ];
    }
}
