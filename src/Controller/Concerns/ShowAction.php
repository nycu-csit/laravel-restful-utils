<?php

namespace NycuCsit\LaravelRestfulUtils\Controller\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @mixin Context
 */
trait ShowAction
{
    /**
     * Show one API resource.
     *
     * Corresponding API may be `GET /api/resources/{id}`.
     *
     * It is not recommended to override this method, you may write a new method 'show' with the parameters you want
     * and call this method.
     *
     * Example:
     * ```php
     * public function show(Request $request, Model $model)
     * {
     *     return parent::showAction($request, $model);
     * }
     * ```
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Database\Eloquent\Model|array|mixed|null
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function showAction(Request $request, Model $model)
    {
        $this->model = $model;
        $this->request = $request;

        $this->validateShowRequest();
        $this->authorizeShowRequest();
        $this->result = $this->model;
        $this->postprocessShowResult();

        return $this->result;
    }

    /**
     * Validate the show request.
     */
    protected function validateShowRequest(): void
    {
    }

    /**
     * Authorize the show request.
     * {@link https://laravel.com/docs/master/authorization#via-controller-helpers via controller helpers}.
     *
     * You must create a {@link https://laravel.com/docs/master/authorization#creating-policies policy} for this method.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function authorizeShowRequest(): void
    {
        $this->authorize('view', $this->model);
    }

    /**
     * Postprocess the model ($this->model)
     *
     * By default, this method will assign $this->result to a
     * {@link https://laravel.com/docs/master/eloquent-resources#resource-responses resource response} for wrapping
     * api data.
     */
    protected function postprocessShowResult(): void
    {
        $this->result = $this->constructJsonResource($this->result);
    }
}
