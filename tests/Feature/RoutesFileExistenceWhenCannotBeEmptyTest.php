<?php

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;
use BonsaiCms\MetamodelEloquentJsonApi\Exceptions\RoutesAlreadyExistsException;

it('deletes the routes file when the last entity is deleted', function () {
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(base_path('routes-custom/api-custom.generated.php'));

    $entity1 = Entity::factory()->create();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(base_path('routes-custom/api-custom.generated.php'));

    $entity2 = Entity::factory()->create();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(base_path('routes-custom/api-custom.generated.php'));

    $entity3 = Entity::factory()->create();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(base_path('routes-custom/api-custom.generated.php'));

    $entity1->delete();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(base_path('routes-custom/api-custom.generated.php'));

    $entity2->delete();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(base_path('routes-custom/api-custom.generated.php'));

    $entity3->delete();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(base_path('routes-custom/api-custom.generated.php'));
});

it('does not generate the routes file when no entities exist', function () {
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(base_path('routes-custom/api-custom.generated.php'));

    app(JsonApiManagerContract::class)->generateRoutes();

    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(base_path('routes-custom/api-custom.generated.php'));
});

it('does not throw an exception when trying to generate a routes file twice, but no entities exist', function () {
    app(JsonApiManagerContract::class)->generateRoutes();
    app(JsonApiManagerContract::class)->generateRoutes();

    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(base_path('routes-custom/api-custom.generated.php'));
});

it('does not throw an exception when trying to generate a routes file twice, but we deleted it before', function () {
    Entity::withoutEvents(function () {
        Entity::factory()->create();
    });

    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(base_path('routes-custom/api-custom.generated.php'));

    app(JsonApiManagerContract::class)->generateRoutes();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(base_path('routes-custom/api-custom.generated.php'));

    app(JsonApiManagerContract::class)->deleteRoutes();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeFalse();
    $this->assertFileDoesNotExist(base_path('routes-custom/api-custom.generated.php'));

    app(JsonApiManagerContract::class)->generateRoutes();
    expect(app(JsonApiManagerContract::class)->routesExist())->toBeTrue();
    $this->assertFileExists(base_path('routes-custom/api-custom.generated.php'));
});
