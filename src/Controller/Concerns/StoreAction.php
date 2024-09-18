<?php

namespace NycuCsit\LaravelRestfulUtils\Controller\Concerns;

use Illuminate\Http\Request;
use ReflectionClass;

/**
 * @mixin Context
 */
trait StoreAction
{
    use SetModelAttributes;

    /**
     * Store an API resource.
     *
     * Corresponding API may be `POST /api/resources`.
     *
     * It is not recommended to override this method, you may write a new method 'store' with the parameters you want
     * and call this method. {@link https://laravel.com/docs/master/validation#form-request-validation FormRequest} is
     * recommended.
     *
     * Example:
     * ```php
     * public function store(StorePostRequest $request)
     * {
     *     return parent::storeAction($request);
     * }
     * ```
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Database\Eloquent\Model|array|mixed|null
     * @throws \ReflectionException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function storeAction(Request $request)
    {
        $this->request = $request;

        $this->validateStoreRequest();
        $this->authorizeStoreRequest();
        $this->createModel();
        $this->processCreatedModel();
        $this->postprocessCreatedModel();
        $this->saveCreatedModel();
        $this->postprocessStoreResult();

        return $this->result;
    }

    /**
     * Validate the store request.
     *
     * We recommend you use {@link https://laravel.com/docs/master/validation#form-request-validation FormRequest} to do
     * validation instead of overriding this method.
     */
    protected function validateStoreRequest(): void
    {
    }

    /**
     * Authorize the store request.
     * {@link https://laravel.com/docs/master/authorization#via-controller-helpers via controller helpers}.
     *
     * You must create a {@link https://laravel.com/docs/master/authorization#creating-policies policy} for this method.
     *
     * You may also use authorize() function in a
     * {@link https://laravel.com/docs/master/validation#form-request-validation FormRequest}.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function authorizeStoreRequest(): void
    {
        $this->authorize('create', $this->getModelClass());
    }

    /**
     * Create a new model and assign to $this->model.
     *
     * This model will be persisted to database in later steps.
     *
     * @throws \ReflectionException
     */
    protected function createModel(): void
    {
        /** @var \Illuminate\Database\Eloquent\Model $reflectionClass */
        $reflectionClass = new ReflectionClass($this->getModelClass());
        $this->model = $reflectionClass->newInstance();
    }

    /**
     * Process the model which was created in previous steps
     *
     * By default, this method sets model's attribute value from $this->request, attributes to be set is from
     * {@see getFillableAttributes()}.
     * You may override this method for assigning attributes to $this->model.
     *
     * getFillableAttributes() is ignored in {@see setAttributesFromRequest()} if $this->request is a
     * {@see \Illuminate\Foundation\Http\FormRequest}.
     */
    protected function processCreatedModel(): void
    {
        $this->setAttributesFromRequest($this->getFillableAttributes());
    }

    /**
     * Returns fillable attribute names of the model
     *
     * You may override this method if you are not using {@see \Illuminate\Foundation\Http\FormRequest}.
     *
     * @return array
     */
    protected function getFillableAttributes(): array
    {
        return $this->model->getFillable();
    }

    /**
     * Postprocess the model whose attributes were assigned in previous steps
     *
     * You may assign some attributes in this method.
     */
    protected function postprocessCreatedModel(): void
    {
    }

    /**
     * Save the model which was processed in previous steps
     *
     * The saved model will be assigned to $this->result
     */
    protected function saveCreatedModel(): void
    {
        $this->model->save();
        $this->result = $this->model;
    }

    /**
     * Postprocess the result (model) after the model saved
     *
     * By default, this method will assign $this->result to a
     * {@link https://laravel.com/docs/master/eloquent-resources#resource-responses resource response} for wrapping
     * api data.
     *
     * Use status code 201 when resource were successfully created.
     */
    protected function postprocessStoreResult(): void
    {
        $this->result = $this->constructJsonResource($this->result)->response()->setStatusCode(201);
    }
}
