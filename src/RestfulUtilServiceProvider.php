<?php

namespace NycuCsit\LaravelRestfulUtils;

use Illuminate\Support\ServiceProvider;
use NycuCsit\LaravelRestfulUtils\Console\Commands\ControllerMakeCommand;

class RestfulUtilServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ControllerMakeCommand::class
            ]);
        }

        $this->publishes([
            __DIR__ . '/../controllers/ApiResourceController.php' =>
                app_path('Http/Controllers/ApiResourceController.php'),
            __DIR__ . '/../controllers/ApiNestedResourceController.php' =>
                app_path('Http/Controllers/ApiNestedResourceController.php')
        ], 'restful-controllers');
    }
}
