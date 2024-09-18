<?php

namespace NycuCsit\LaravelRestfulUtils\Controller\Concerns;

use Illuminate\Http\Request;

/**
 * @mixin Context
 */
trait IndexAction
{
    use Pagination;

    /**
     * Index the API resources.
     *
     * Corresponding API may be `GET /api/resources`.
     *
     * It is not recommended to override this method, you may write a new method 'index' with the parameters you want
     * and call this method.
     *
     * Example:
     * ```php
     * public function index(Request $request)
     * {
     *     return parent::indexAction($request);
     * }
     * ```
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\ResourceCollection|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|array|mixed|null
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function indexAction(Request $request)
    {
        $this->request = $request;

        $this->validateIndexRequest();
        $this->authorizeIndexRequest();
        $this->setupQueryBuilder();
        $this->query = $this->buildIndexQuery() ?? $this->query;
        $this->executeGetQuery();
        $this->postprocessIndexResult();

        return $this->result;
    }

    /**
     * Validate the index request.
     *
     * You may override {@see indexRequestValidationRule()} to provide validation rules.
     *
     * @throws \Illuminate\Validation\ValidationException if the validation failed
     */
    protected function validateIndexRequest(): void
    {
        $this->request->validate(array_merge([
            // Pagination parameters
            'limit' => ['integer', 'min:1', 'max:' . $this->maxLimit],
            'page' => 'integer|min:1'
        ], $this->indexRequestValidationRule()));
    }

    /**
     * Returns additional validation rules to be used in {@see validateIndexRequest()}.
     *
     * @return array validation rules, default value is an empty array.
     */
    protected function indexRequestValidationRule(): array
    {
        return [];
    }

    /**
     * Authorize the index request
     * {@link https://laravel.com/docs/master/authorization#via-controller-helpers via controller helpers}.
     *
     * You must create a {@link https://laravel.com/docs/master/authorization#creating-policies policy} for this method.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function authorizeIndexRequest(): void
    {
        $this->authorize('viewAny', $this->getModelClass());
    }

    /**
     * Assign the model's query builder to $this->query.
     */
    protected function setupQueryBuilder(): void
    {
        $this->query = call_user_func($this->getModelClass() . '::' . 'query');
    }

    /**
     * Build index query by request or other criteria.
     *
     * Example:
     * ```php
     * public abstract function buildIndexQuery(): object
     * {
     *     // Query posts by publishing status
     *     $published = $this->request->boolean('published');
     *     // You may use $this->query which was assigned in setupQueryBuilder()
     *     return $this->query->where('published', $published);
     * }
     * ```
     *
     * @return object|null the query builder to be assigned to $this->query
     */
    abstract public function buildIndexQuery(): ?object;

    /**
     * Execute the query in $this->query and assign the query result to $this->result.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function executeGetQuery(): void
    {
        if ($this->needPagination($this->request)) {
            $this->getPaginated($this->request);
        } else {
            $this->result = $this->query->get();
        }
    }

    /**
     * Postprocess the query result ($this->result) after the query is executed.
     *
     * By default, this method will assign $this->result to a
     * {@link https://laravel.com/docs/master/eloquent-resources#resource-collections resource collection} for wrapping
     * api data.
     */
    protected function postprocessIndexResult(): void
    {
        $this->result = $this->constructResourceCollection($this->result);
    }
}
