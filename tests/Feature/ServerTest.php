<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

it('generates a skeleton server file even though no entities exist', function () {
    app(JsonApiManagerContract::class)->generateServer();

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/server/Skeleton.php',
        actual: base_path('app/JsonApi/TestApi/TestServer.generated.php')
    );
});

it('generates a server file with schemas', function () {
    app(JsonApiManagerContract::class)->generateServer();

    Entity::factory()->create([
        'name' => 'BlueDog',
    ]);

    Entity::factory()->create([
        'name' => 'RedCat',
    ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/server/WithSchemas.php',
        actual: base_path('app/JsonApi/TestApi/TestServer.generated.php')
    );
});

it('generates a skeleton server file when all entities were deleted', function () {
    app(JsonApiManagerContract::class)->generateServer();

    $entity1 = Entity::factory()->create();
    $entity2 = Entity::factory()->create();
    $entity3 = Entity::factory()->create();

    $entity1->delete();
    $entity2->delete();
    $entity3->delete();

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/server/Skeleton.php',
        actual: base_path('app/JsonApi/TestApi/TestServer.generated.php')
    );
});
