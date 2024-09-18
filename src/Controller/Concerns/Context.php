<?php

namespace NycuCsit\LaravelRestfulUtils\Controller\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

/**
 * @mixin \Illuminate\Routing\Controller
 * @mixin \Illuminate\Foundation\Auth\Access\AuthorizesRequests
 */
trait Context
{
    /**
     * The model which should be processed.
     *
     * - SHOW, UPDATE and DESTROY action: $model is from route model binding.
     * - STORE action: $model is created in function {@see StoreAction::createModel() StoreAction::createModel()}
     * - INDEX and other actions: not used.
     *
     * @var Model|null
     */
    public ?Model $model = null;

    /**
     * The model which has one or many related models.
     *
     * - Nested INDEX action: $parentModel is from route model binding and is used to get related model/models.
     * - Nested STORE action: the resource to be stored will be attached to $parentModel in function
     * {@see StoreAction::saveCreatedModel() StoreAction::saveCreatedModel()}
     * - Other actions: not used.
     *
     * {@link https://laravel.com/docs/master/controllers#shallow-nesting Swallow nesting} is strongly recommended.
     *
     * @var Model|null
     */
    public ?Model $parentModel = null;

    /**
     * The query builder for INDEX action.
     *
     * $query will be set up in function {@see IndexAction::setupQueryBuilder() IndexAction::setupQueryBuilder()}.
     *
     * You can override {@see IndexAction::buildIndexQuery() IndexAction::buildIndexQuery()}
     * to add search criteria.
     *
     * @var Builder|Relation|null
     */
    public $query = null;

    /**
     * The result object to be responded.
     *
     * It is recommended to use
     * {@link https://laravel.com/docs/master/eloquent-resources#resource-responses resource response}.
     *
     * @var \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\Resources\Json\ResourceCollection|Model|\Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse|string|array|null|mixed
     */
    public $result = null;

    /**
     * The http request object.
     *
     * $request may be a {@link https://laravel.com/docs/master/validation#form-request-validation FormRequest}.
     */
    public Request $request;
}
