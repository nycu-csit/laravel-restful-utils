<?php

namespace NycuCsit\LaravelRestfulUtils\Controller\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\ValidatedInput;

/**
 * @property Model $model
 * @property Request $request
 */
trait SetModelAttributes
{
    /**
     * Set attributes of $this->model from the inputs of $this->request
     *
     * @param array|null $allowedAttributes Allowed to be assigned attributes. Any attribute is allowed if
     *                                      $allowedAttributes is null. $allowedAttributes is ignored if $this->request
     *                                      is a {@see FormRequest FormRequest}.
     */
    protected function setAttributesFromRequest(?array $allowedAttributes): void
    {
        $inputs = $this->getInputs();
        $attributes = $this->getToBeAssignedAttributes($inputs, $allowedAttributes);
        foreach ($attributes as $attribute) {
            $value = $inputs[$attribute];
            $this->model->setAttribute($attribute, $value);
        }
    }

    /**
     * Get validated inputs in $this->request
     *
     * @return array
     * @internal DO NOT override this method, this method is only for {@see setAttributesFromRequest()}
     */
    protected function getInputs(): array
    {
        $inputs = $this->request instanceof FormRequest ? $this->request->safe() : $this->request->input();
        return $inputs instanceof ValidatedInput ? $inputs->toArray() : $inputs;
    }

    /**
     * Get attributes which will be assigned from $this->request to $this->model
     *
     * @param array $inputs
     * @param array|null $allowedAttributes
     * @return array
     * @internal DO NOT override this method, this method is only for {@see setAttributesFromRequest()}
     */
    private function getToBeAssignedAttributes(array $inputs, ?array $allowedAttributes): array
    {
        $inputKeys = array_keys($inputs);
        $allowAny = is_null($allowedAttributes);
        return
            $this->request instanceof FormRequest || $allowAny ?
                $inputKeys :
                array_intersect(array_keys($inputs), $allowedAttributes);
    }
}
