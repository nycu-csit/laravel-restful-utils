<?php

namespace App\Http\Controllers;

use NycuCsit\LaravelRestfulUtils\Controller\HasResourceActions;

abstract class ApiResourceController extends Controller
{
    use HasResourceActions;
}
