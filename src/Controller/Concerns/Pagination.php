<?php

namespace NycuCsit\LaravelRestfulUtils\Controller\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Pagination feature
 *
 * @property Model $model
 * @property mixed $result
 */
trait Pagination
{
    /**
     * @var bool Indicate whether to always paginate the result.
     * @see needPagination()
     */
    protected bool $alwaysPaginate = true;

    /**
     * @var bool Indicate whether to enable the pagination, this property will be ignored when $alwaysPaginate == true
     * @see needPagination()
     */
    protected bool $enablePaginate = true;

    /**
     * @var int Default amount of resources in one response
     */
    protected int $defaultLimit = 20;

    /**
     * @var int Maximum amount of resource in one response
     */
    protected int $maxLimit = 100;

    /**
     * Indicate whether to paginate the query result by the request and configuration
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function needPagination(Request $request): bool
    {
        return $this->alwaysPaginate ||
            ($this->enablePaginate && ($request->has('limit') || $request->has('page')));
    }

    /**
     * Set paginated query result from $this->query to $this->result
     *
     * @throws ValidationException
     */
    protected function getPaginated(Request $request): void
    {
        $this->validatePaginationParameters($request);

        $per_page = intval($request->query('limit', strval($this->defaultLimit)));
        $per_page = min($per_page, $this->maxLimit);

        /**
         * Here a "Call to an undefined method" error will be reported.
         * withQueryString() function only exists in {@see \Illuminate\Pagination\LengthAwarePaginator implementation}
         * but doesn't exist in {@see \Illuminate\Contracts\Pagination\LengthAwarePaginator interface}
         */
        /* @phpstan-ignore-next-line */
        $this->result = $this->query->paginate($per_page)->withQueryString();
    }

    /**
     * Validate pagination query strings
     *
     * @throws ValidationException
     */
    protected function validatePaginationParameters(Request $request): void
    {
        Validator::make($request->only('limit', 'page'), [
            'limit' => ['integer', 'min:1', 'max:' . $this->maxLimit],
            'page' => 'integer|min:1'
        ])->validate();
    }
}
