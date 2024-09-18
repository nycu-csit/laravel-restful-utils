<?php

namespace NycuCsit\LaravelRestfulUtils\Controller;

/**
 * This class is for PHPStan.  You should use the published class.
 *
 * @mixin \Illuminate\Foundation\Auth\Access\AuthorizesRequests
 * @phpstan-ignore-next-line see: https://github.com/phpstan/phpstan/issues/6778
 */
abstract class ApiNestedResourceController
{
    use HasNestedResourceActions;
}
