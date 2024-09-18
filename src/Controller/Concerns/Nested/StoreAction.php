<?php

namespace NycuCsit\LaravelRestfulUtils\Controller\Concerns\Nested;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use NycuCsit\LaravelRestfulUtils\Controller\Concerns\SetModelAttributes;
use NycuCsit\LaravelRestfulUtils\Controller\Concerns\StoreAction as BaseStoreAction;

/**
 * @mixin \NycuCsit\LaravelRestfulUtils\Controller\Concerns\Context
 */
trait StoreAction
{
    use SetModelAttributes;
    use BaseStoreAction;

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Model $parentModel
     * @return \Illuminate\Http\Resources\Json\JsonResource|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Database\Eloquent\Model|array|mixed|null
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ReflectionException
     */
    public function storeAction(Request $request, Model $parentModel)
    {
        $this->request = $request;
        $this->parentModel = $parentModel;

        $this->validateStoreRequest();
        $this->authorizeStoreRequest();
        $this->createModel();
        $this->processCreatedModel();
        $this->postprocessCreatedModel();
        $this->saveCreatedModel();
        $this->result = $this->model;
        $this->postprocessStoreResult();

        return $this->result;
    }

    /**
     * Save the model which was processed in previous steps and attach it to the parent model.
     *
     * Example:
     * ```php
     * protected function saveCreatedModel(): void
     * {
     *     // Save a comment to the post
     *     $this->parentModel->comments()->save($this->model);
     * }
     * ```
     */
    abstract protected function saveCreatedModel(): void;
}
