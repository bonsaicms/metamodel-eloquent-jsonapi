<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;

it('creates a basic schema when a new entity is created', function () {
    Entity::factory()->create([
        'name' => 'Article',
    ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/schemas/BasicSchema.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/Articles/ArticleSchema.generated.php'
    );
});

it('creates a schema with attributes when a new entity with attributes is created', function () {
    $entity = Entity::factory()->create([
        'name' => 'Page',
    ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_string_attribute',
            'data_type' => 'string',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_text_attribute',
            'data_type' => 'text',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_boolean_attribute',
            'data_type' => 'boolean',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_integer_attribute',
            'data_type' => 'integer',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_date_attribute',
            'data_type' => 'date',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_time_attribute',
            'data_type' => 'time',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_datetime_attribute',
            'data_type' => 'datetime',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/schemas/SchemaWithAttributes.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/Pages/PageSchema.generated.php'
    );
});
