<?php

namespace Tests\Exceptions;

use InvalidArgumentException;
use NycuCsit\LaravelRestfulUtils\Exceptions\HttpApiException;
use PHPUnit\Framework\TestCase;

class HttpApiExceptionTest extends TestCase
{
    public function test_constructor_invalid_int_code()
    {
        $this->expectException(InvalidArgumentException::class);

        new HttpApiException(200, 7);
    }

    /**
     * @requires PHP 8.1
     */
    public function test_constructor_invalid_int_backed_enum_code()
    {
        $this->expectException(InvalidArgumentException::class);

        new HttpApiException(200, \Tests\TestIntEnum::Test2);
    }

    public function test_constructor_code()
    {
        $e = new HttpApiException(200);
        $this->assertEquals('OK', $e->errorCode);

        new HttpApiException(500, null);
        new HttpApiException(200, null);
    }

    /**
     * @requires PHP 8.1
     */
    public function test_constructor_enum_code()
    {
        $e = new HttpApiException(200, \Tests\TestEnum::Test1);
        $this->assertEquals(\Tests\TestEnum::Test1->value, $e->errorCode);
    }
}
