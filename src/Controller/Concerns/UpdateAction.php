<?php

namespace NycuCsit\LaravelRestfulUtils\Controller\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @mixin Context
 */
trait UpdateAction
{
    use SetModelAttributes;

    /**
     * Update an API resource.
     *
     * Corresponding API may be `PATCH /api/resources`.
     *
     * It is not recommended to override this method, you may write a new method 'update' with the parameters you want
     * and call this method. {@link https://laravel.com/docs/master/validation#form-request-validation FormRequest} is
     * recommended.
     *
     * Example:
     * ```php
     * public function update(UpdatePostRequest $request, Model $model)
     * {
     *     return parent::updateAction($request, $model);
     * }
     * ```
     *
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Database\Eloquent\Model|array|mixed|null
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateAction(Request $request, Model $model)
    {
        $this->request = $request;
        $this->model = $model;

        $this->validateUpdateRequest();
        $this->authorizeUpdateRequest();
        $this->updateModel();
        $this->postprocessUpdatedModel();
        $this->saveUpdatedModel();
        $this->postprocessUpdateResult();

        return $this->result;
    }

    /**
     * Validate the update request.
     *
     * We recommend you use {@link https://laravel.com/docs/master/validation#form-request-validation FormRequest} to do
     * validation instead of overriding this method.
     */
    protected function validateUpdateRequest(): void
    {
    }

    /**
     * Authorize the update request.
     * {@link https://laravel.com/docs/master/authorization#via-controller-helpers via controller helpers}.
     *
     * You must create a {@link https://laravel.com/docs/master/authorization#creating-policies policy} for this method.
     *
     * You may also use authorize() function in a
     * {@link https://laravel.com/docs/master/validation#form-request-validation FormRequest}.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function authorizeUpdateRequest(): void
    {
        $this->authorize('update', $this->model);
    }

    /**
     * Update the model.
     *
     * The changes of the model will be persisted to database in later steps.
     *
     * By default, this method sets model's attribute value from $this->request, attributes to be set is from
     * {@see getUpdatableAttributes()}.
     * You may override this method for assigning attributes to $this->model.
     *
     * getUpdatableAttributes() is ignored in {@see setAttributesFromRequest()} if $this->request is a
     * {@see \Illuminate\Foundation\Http\FormRequest}.
     */
    protected function updateModel(): void
    {
        $this->setAttributesFromRequest($this->getUpdatableAttributes());
    }

    /**
     * Returns updatable attribute names of the model
     *
     * You may override this method if you are not using {@see \Illuminate\Foundation\Http\FormRequest}.
     *
     * @return array
     */
    protected function getUpdatableAttributes(): array
    {
        return $this->model->getFillable();
    }

    /**
     * Postprocess the model whose attributes were assigned in previous steps
     *
     * You may assign some attributes in this method.
     */
    protected function postprocessUpdatedModel(): void
    {
    }

    /**
     * Save the model which was processed in previous steps
     *
     * The saved model will be assigned to $this->result
     */
    protected function saveUpdatedModel(): void
    {
        $this->model->save();
        $this->result = $this->model;
    }

    /**
     * Postprocess the result (model) after the model updated and saved
     *
     * By default, this method will assign $this->result to a
     * {@link https://laravel.com/docs/master/eloquent-resources#resource-responses resource response} for wrapping
     * api data.
     */
    protected function postprocessUpdateResult(): void
    {
        $this->result = $this->constructJsonResource($this->result);
    }
}
