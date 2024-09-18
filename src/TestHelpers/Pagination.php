<?php

namespace NycuCsit\LaravelRestfulUtils\TestHelpers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Testing\TestResponse;

/**
 * @method assertFalse(bool $actual)
 */
trait Pagination
{
    public function assertResourcePaginated(TestResponse $response)
    {
        $validator = Validator::make($response->json(), [
            'links.first' => 'required|url',
            'links.last' => 'required|url',
            'links.next' => 'nullable|url',
            'links.prev' => 'nullable|url',
            'meta.total' => 'required|integer|min:0',
            'meta.per_page' => 'required|integer|min:0',
            'meta.current_page' => 'required|integer|min:0',
            'meta.last_page' => 'required|integer|min:0',
            'meta.path' => 'required|url',
            'meta.from' => 'required|integer',
            'meta.to' => 'required|integer',
            'data' => 'required|array'
        ]);
        $this->assertFalse($validator->fails());
    }
}
