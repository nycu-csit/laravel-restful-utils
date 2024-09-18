<?php

namespace NycuCsit\LaravelRestfulUtils\Controller;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use NycuCsit\LaravelRestfulUtils\Controller\Concerns\Context;
use NycuCsit\LaravelRestfulUtils\Controller\Concerns\DestroyAction;
use NycuCsit\LaravelRestfulUtils\Controller\Concerns\Nested\IndexAction as NestedIndexAction;
use NycuCsit\LaravelRestfulUtils\Controller\Concerns\Nested\StoreAction as NestedStoreAction;
use NycuCsit\LaravelRestfulUtils\Controller\Concerns\Pagination;
use NycuCsit\LaravelRestfulUtils\Controller\Concerns\ShowAction;
use NycuCsit\LaravelRestfulUtils\Controller\Concerns\UpdateAction;

/**
 * Implements
 * {@link https://laravel.com/docs/master/controllers#restful-nested-resources resource controllers of nested resources}
 *
 * @mixin \Illuminate\Routing\Controller
 */
trait HasNestedResourceActions
{
    use Context;
    use Pagination;

    use NestedStoreAction;
    use ShowAction;
    use NestedIndexAction;
    use UpdateAction;
    use DestroyAction;

    /**
     * Construct a {@link https://laravel.com/docs/master/eloquent-resources#resource-responses resource response} as
     * API response.
     *
     * You may override this method for your resource.
     *
     * Example:
     * ```php
     * public function constructJsonResource($resource): JsonResource
     * {
     *     return new \App\Http\Resources\PostResource($resource);
     * }
     * ```
     *
     * @param mixed $resource the result from previous steps
     * @return \Illuminate\Http\Resources\Json\JsonResource object to be responded to the client
     */
    public function constructJsonResource($resource): JsonResource
    {
        return new JsonResource($resource);
    }

    /**
     * Construct a {@link https://laravel.com/docs/master/eloquent-resources#resource-collections resource collections}
     * as API response.
     *
     * You may override this method for your resource collections.
     *
     * Example:
     * ```php
     * public function constructResourceCollection($resource): ResourceCollection
     * {
     *     return \App\Http\Resources\PostResource::collection($resource);
     * }
     * ```
     *
     * @param mixed $resource the result from previous steps
     * @return \Illuminate\Http\Resources\Json\ResourceCollection object to be responded to the client
     */
    public function constructResourceCollection($resource): ResourceCollection
    {
        return new ResourceCollection($resource);
    }

    /**
     * Returns the fully qualified name of the model class.
     *
     * You MUST implement this method.
     *
     * Example:
     * ```php
     * protected abstract function getModelClass(): string
     * {
     *     return \App\Models\Post::class;
     * }
     * ```
     *
     * This method is used for:
     * - {@link https://laravel.com/docs/master/authorization#via-controller-helpers Authorizing}
     * in authorize{Action}Request() functions
     * - {@see \NycuCsit\LaravelRestfulUtils\Controller\Concerns\IndexAction::setupQueryBuilder()
     * IndexAction::setupQueryBuilder()}
     * - {@see \NycuCsit\LaravelRestfulUtils\Controller\Concerns\StoreAction::createModel() StoreAction::createModel()}
     *
     * @return class-string<\Illuminate\Database\Eloquent\Model>
     */
    abstract protected function getModelClass(): string;
}
