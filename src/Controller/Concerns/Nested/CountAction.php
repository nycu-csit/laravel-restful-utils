<?php

namespace NycuCsit\LaravelRestfulUtils\Controller\Concerns\Nested;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use NycuCsit\LaravelRestfulUtils\Controller\Concerns\CountAction as BaseCountAction;

/**
 * @mixin \NycuCsit\LaravelRestfulUtils\Controller\Concerns\Context
 * @mixin \Illuminate\Routing\Controller
 * @mixin \NycuCsit\LaravelRestfulUtils\Controller\Concerns\Nested\IndexAction
 */
trait CountAction
{
    use BaseCountAction;

    public function countAction(Request $request, Model $parentModel)
    {
        $this->request = $request;
        $this->parentModel = $parentModel;

        $this->validateCountRequest();
        $this->authorizeCountRequest();
        $this->setupQueryBuilder();
        $this->buildCountQuery();
        $this->executeCountQuery();
        $this->postprocessCountResult();

        return $this->result;
    }
}
