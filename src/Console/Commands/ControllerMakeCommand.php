<?php

namespace NycuCsit\LaravelRestfulUtils\Console\Commands;

class ControllerMakeCommand extends \Illuminate\Routing\Console\ControllerMakeCommand
{
    protected static $defaultName = 'make:restful-controller';
    protected $name = 'make:restful-controller';

    protected function getStub(): string
    {
        return __DIR__ . ($this->option('parent') ?
                "/stubs/controller.nested-res-api.stub" : "/stubs/controller.res-api.stub");
    }

    protected function getOptions(): array
    {
        $excludes = ['api', 'type', 'invokable', 'resource'];
        return array_filter(parent::getOptions(), fn($item) => !in_array($item[0], $excludes));
    }

    protected function buildFormRequestReplacements(array $replace, $modelClass): array
    {
        $replaces = parent::buildFormRequestReplacements($replace, $modelClass);
        if ($this->option('requests')) {
            $replaces['{{ namespacedRequests }}'] .= PHP_EOL . 'use Illuminate\Http\Request;';
        }
        return $replaces;
    }
}
