<?php

namespace App\Http\Controllers;

use NycuCsit\LaravelRestfulUtils\Controller\HasNestedResourceActions;

abstract class ApiNestedResourceController extends Controller
{
    use HasNestedResourceActions;
}
