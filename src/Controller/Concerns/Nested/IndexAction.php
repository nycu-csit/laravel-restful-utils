<?php

namespace NycuCsit\LaravelRestfulUtils\Controller\Concerns\Nested;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use NycuCsit\LaravelRestfulUtils\Controller\Concerns\IndexAction as BaseIndexAction;
use NycuCsit\LaravelRestfulUtils\Controller\Concerns\Pagination;

/**
 * @mixin \NycuCsit\LaravelRestfulUtils\Controller\Concerns\Context
 */
trait IndexAction
{
    use Pagination;
    use BaseIndexAction;

    /**
     * Index the API resources which belongs to parent resource.
     *
     * Corresponding API may be `GET /api/parent-resource/{id}/resources`.
     *
     * It is not recommended to override this method, you may write a new method 'index' with the parameters you want
     * and call this method.
     *
     * Example:
     * ```php
     * public function index(Request $request, Post $post)
     * {
     *     return parent::indexAction($request, $post);
     * }
     * ```
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Model $parentModel
     * @return \Illuminate\Http\Resources\Json\ResourceCollection|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|array|mixed|null
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function indexAction(Request $request, Model $parentModel)
    {
        $this->request = $request;
        $this->parentModel = $parentModel;

        $this->validateIndexRequest();
        $this->authorizeIndexRequest();
        $this->setupQueryBuilder();
        $this->query = $this->buildIndexQuery() ?? $this->query;
        $this->executeGetQuery();
        $this->postprocessIndexResult();

        return $this->result;
    }

    /**
     * Set up query builder ($this->query)
     *
     * You must implement this method to query the resources which belongs to $this->parentModel.
     *
     * Example:
     * ```php
     * protected function setupQueryBuilder(): void
     * {
     *     // Comments is a BelongsTo relationship of $this->parentModel
     *     $this->query = $this->parentModel->comments();
     * }
     * ```
     */
    abstract protected function setupQueryBuilder(): void;

    /**
     * Build index query by request or other criteria.
     *
     * Example:
     * ```php
     * public function buildIndexQuery(): object
     * {
     *     // Query comments by publishing status
     *     $published = $this->request->boolean('published');
     *     // You may use $this->query which was assigned in setupQueryBuilder()
     *     return $this->query->where('published', $published);
     * }
     * ```
     *
     * @return object|null the query builder to be assigned to $this->query
     */
    abstract public function buildIndexQuery(): ?object;
}
