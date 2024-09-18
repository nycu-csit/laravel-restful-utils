<?php

namespace NycuCsit\LaravelRestfulUtils\Casts;

use Carbon\CarbonInterface;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Date;

/**
 * @implements CastsAttributes<\Carbon\CarbonInterface, \Carbon\CarbonInterface>
 */
class LocalDatetime implements CastsAttributes
{
    public const IMMUTABLE = ':true,true';
    public const DATE_ONLY_IMMUTABLE = ':false,true';
    public const DATE_ONLY = ':false,false';

    /**
     * @param bool $time Indicate whether the value is with time
     * @param bool $immutable Indicate whether to use immutable instance
     */
    public function __construct(public bool $time = true, public bool $immutable = false)
    {
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $this->cast($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $this->cast($value);
    }

    /**
     * @param mixed $value
     * @return \Carbon\CarbonInterface|null
     */
    protected function cast(mixed $value): CarbonInterface|null
    {
        if (is_null($value)) {
            return null;
        }

        if (is_string($value)) {
            $value = Date::parse($value);
        }

        $value = Date::instance($value)->setTimezone(config('app.timezone'));

        if (! $this->time) {
            $value = $value->startOfDay();
        }

        if ($this->immutable) {
            $value = $value->toImmutable();
        }

        return $value;
    }
}
