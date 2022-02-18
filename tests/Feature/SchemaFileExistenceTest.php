<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;
use BonsaiCms\MetamodelEloquentJsonApi\Exceptions\SchemaAlreadyExistsException;

it('creates a schema file when a new entity is created', function () {
    $entity = Entity::factory()->create([
        'name' => 'TestEntity',
    ]);

    expect(app(JsonApiManagerContract::class)->schemaExists($entity))->toBeTrue();
    $this->assertFileExists(base_path('app/JsonApi/TestApi/TestEntities/TestEntitySchema.generated.php'));
});

it('deletes the schema file when the entity is deleted', function () {
    $entity = tap(Entity::factory()
        ->create([
            'name' => 'TestEntity',
        ]))
        ->delete();

    expect(app(JsonApiManagerContract::class)->schemaExists($entity))->toBeFalse();
    $this->assertFileDoesNotExist(base_path('app/JsonApi/TestApi/TestEntities/TestEntitySchema.generated.php'));
});

it('throws an exception when generating a schema for entity which already exists', function () {
    expect(function () {
        $entity = Entity::factory()
            ->create([
                'name' => 'TestEntity',
            ]);
        app(JsonApiManagerContract::class)->generateSchema($entity);
    })->toThrow(SchemaAlreadyExistsException::class);
});
