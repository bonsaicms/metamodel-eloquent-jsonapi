<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;
use BonsaiCms\MetamodelEloquentJsonApi\Exceptions\RequestAlreadyExistsException;

it('creates a request file when a new entity is created', function () {
    $entity = Entity::factory()->create([
        'name' => 'TestEntity',
    ]);

    expect(app(JsonApiManagerContract::class)->requestExists($entity))->toBeTrue();
    $this->assertFileExists(base_path('app/JsonApi/TestApi/TestEntities/TestEntityRequest.generated.php'));
});

it('deletes the request file when the entity is deleted', function () {
    $entity = tap(Entity::factory()
        ->create([
            'name' => 'TestEntity',
        ]))
        ->delete();

    expect(app(JsonApiManagerContract::class)->requestExists($entity))->toBeFalse();
    $this->assertFileDoesNotExist(base_path('app/JsonApi/TestApi/TestEntities/TestEntityRequest.generated.php'));
});

it('throws an exception when generating a request for entity which already exists', function () {
    expect(function () {
        $entity = Entity::factory()
            ->create([
                'name' => 'TestEntity',
            ]);
        app(JsonApiManagerContract::class)->generateRequest($entity);
    })->toThrow(RequestAlreadyExistsException::class);
});
