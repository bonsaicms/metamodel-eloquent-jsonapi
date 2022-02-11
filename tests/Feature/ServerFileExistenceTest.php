<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

it('generates a server file even though no entities exist', function () {
    expect(app(JsonApiManagerContract::class)->serverExists())->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/TestServer.generated.php');

    app(JsonApiManagerContract::class)->generateServer();

    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/TestServer.generated.php');
    expect(app(JsonApiManagerContract::class)->serverExists())->toBeTrue();
});

it('creates a server file when created a new entity', function () {
    expect(app(JsonApiManagerContract::class)->serverExists())->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/TestServer.generated.php');

    Entity::factory()->create();

    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/TestServer.generated.php');
    expect(app(JsonApiManagerContract::class)->serverExists())->toBeTrue();
});

it('does not delete the server file even though the last entity was deleted', function () {
    expect(app(JsonApiManagerContract::class)->serverExists())->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/TestServer.generated.php');

    $entity1 = Entity::factory()->create();
    $entity2 = Entity::factory()->create();
    $entity3 = Entity::factory()->create();

    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/TestServer.generated.php');
    expect(app(JsonApiManagerContract::class)->serverExists())->toBeTrue();

    $entity1->delete();
    $entity2->delete();
    $entity3->delete();

    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/TestServer.generated.php');
    expect(app(JsonApiManagerContract::class)->serverExists())->toBeTrue();
});
