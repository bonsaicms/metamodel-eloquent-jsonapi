<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;
use BonsaiCms\MetamodelEloquentJsonApi\Exceptions\RoutesAlreadyExistsException;

it('creates a routes file when the first entity is created', function () {
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');

    Entity::factory()->create([
        'name' => 'TestEntity',
    ]);

    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');
});

it('deletes the routes file when the last entity is deleted', function () {
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');

    $entity1 = Entity::factory()->create();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');

    $entity2 = Entity::factory()->create();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');

    $entity3 = Entity::factory()->create();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');

    $entity1->delete();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');

    $entity2->delete();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');

    $entity3->delete();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');
});

it('does not generate the routes file when no entities exist', function () {
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');

    app(JsonApiManagerContract::class)->generateRoutes();

    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');
});

it('generates the routes file when some entity exists', function () {
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');

    Entity::withoutEvents(function () {
        Entity::factory()->create();
    });

    app(JsonApiManagerContract::class)->generateRoutes();

    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');
});

it('does not throw an exception when trying to generate a routes file twice, but no entities exist', function () {
    app(JsonApiManagerContract::class)->generateRoutes();
    app(JsonApiManagerContract::class)->generateRoutes();

    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');
});

it('does not throw an exception when trying to generate a routes file twice, but we deleted it before', function () {
    Entity::withoutEvents(function () {
        Entity::factory()->create();
    });

    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');

    app(JsonApiManagerContract::class)->generateRoutes();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');

    app(JsonApiManagerContract::class)->deleteRoutes();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');

    app(JsonApiManagerContract::class)->generateRoutes();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php');
});

it('throws an exception when trying to generate a routes file, but it already exists', function () {
    Entity::withoutEvents(function () {
        Entity::factory()->create();
    });

    expect(function () {
        app(JsonApiManagerContract::class)->generateRoutes();
        app(JsonApiManagerContract::class)->generateRoutes();
    })->toThrow(RoutesAlreadyExistsException::class);
});
