<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;
use BonsaiCms\MetamodelEloquentJsonApi\Exceptions\RoutesAlreadyExistsException;

it('creates a routes file when the first entity is created', function () {
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(base_path('routes-custom/api-custom.generated.php'));

    Entity::factory()->create([
        'name' => 'TestEntity',
    ]);

    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(base_path('routes-custom/api-custom.generated.php'));
});

it('generates the routes file when some entity exists', function () {
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(base_path('routes-custom/api-custom.generated.php'));

    Entity::withoutEvents(function () {
        Entity::factory()->create();
    });

    app(JsonApiManagerContract::class)->generateRoutes();

    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(base_path('routes-custom/api-custom.generated.php'));
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
