<?php

use BonsaiCms\MetamodelEloquentJsonApi\Tests\TestCase;

uses(TestCase::class)->in(__DIR__.'/Feature');

uses()->beforeEach(function () {
    config()->set('bonsaicms-metamodel-eloquent-jsonapi.generate.routes.canBeEmpty', true);
})->in(__DIR__.'/Feature/RoutesFileCanBeEmpty');
