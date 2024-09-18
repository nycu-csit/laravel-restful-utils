<?php

namespace NycuCsit\LaravelRestfulUtils\Controller\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @mixin Context
 */
trait DestroyAction
{
    /**
     * Destroy one API resource.
     *
     * Corresponding API may be `DELETE /api/resources/{id}`.
     *
     * It is not recommended to override this method, you may write a new method 'destroy' with the parameters you want
     * and call this method.
     *
     * Example:
     * ```php
     * public function destroy(Request $request, Model $model)
     * {
     *     return parent::destroyAction($request, $model);
     * }
     * ```
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Database\Eloquent\Model|array|mixed|null
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroyAction(Request $request, Model $model)
    {
        $this->request = $request;
        $this->model = $model;

        $this->validateDestroyRequest();
        $this->authorizeDestroyRequest();
        $this->deleteModel();
        $this->postprocessDestroyResult();

        return $this->result;
    }

    /**
     * Validate the destroy request.
     */
    protected function validateDestroyRequest(): void
    {
    }

    /**
     * Authorize the destroy request.
     * {@link https://laravel.com/docs/master/authorization#via-controller-helpers via controller helpers}.
     *
     * You must create a {@link https://laravel.com/docs/master/authorization#creating-policies policy} for this method.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function authorizeDestroyRequest(): void
    {
        $this->authorize('delete', $this->model);
    }

    /**
     * Delete the model from database.
     */
    protected function deleteModel(): void
    {
        $this->model->delete();
    }

    /**
     * Postprocess the model ($this->model)
     *
     * By default, this method will assign $this->result to null.
     */
    protected function postprocessDestroyResult(): void
    {
        $this->result = null;
    }
}
