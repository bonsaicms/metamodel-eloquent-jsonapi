<?php

use BonsaiCms\MetamodelEloquentJsonApi\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

uses()->beforeEach(function () {
    config()->set('bonsaicms-metamodel-eloquent-jsonapi.generate.routes.canBeEmpty', true);
})->in(__DIR__.'/Feature/RoutesFileCanBeEmpty');

function generated_path($path) {
    return __DIR__.'/generated/'.$path;
}
