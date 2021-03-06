<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;

it('creates a basic request when a new entity is created', function () {
    Entity::factory()->create([
        'name' => 'Article',
    ]);

    $this->assertFileEquals(
        expected: generated_path('requests/BasicRequest.php'),
        actual: base_path('app/JsonApi/TestApi/Articles/ArticleRequest.generated.php')
    );
});

it('creates a request with attribute rules when a new entity with attributes is created', function () {
    $entity = Entity::factory()->create([
        'name' => 'Page',
    ]);

    /*
     * String
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_string_attribute',
            'data_type' => 'string',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_string_attribute',
            'data_type' => 'string',
            'nullable' => false,
        ]);

    /*
     * Text
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_text_attribute',
            'data_type' => 'text',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_text_attribute',
            'data_type' => 'text',
            'nullable' => false,
        ]);

    /*
     * Boolean
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_boolean_attribute',
            'data_type' => 'boolean',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_boolean_attribute',
            'data_type' => 'boolean',
            'nullable' => false,
        ]);

    /*
     * Integer
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_integer_attribute',
            'data_type' => 'integer',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_integer_attribute',
            'data_type' => 'integer',
            'nullable' => false,
        ]);

    /*
     * Date
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_date_attribute',
            'data_type' => 'date',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_date_attribute',
            'data_type' => 'date',
            'nullable' => false,
        ]);

    /*
     * Time
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_time_attribute',
            'data_type' => 'time',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_time_attribute',
            'data_type' => 'time',
            'nullable' => false,
        ]);

    /*
     * DateTime
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_datetime_attribute',
            'data_type' => 'datetime',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_datetime_attribute',
            'data_type' => 'datetime',
            'nullable' => false,
        ]);

    /*
     * ArrayList
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_arraylist_attribute',
            'data_type' => 'arraylist',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_arraylist_attribute',
            'data_type' => 'arraylist',
            'nullable' => false,
        ]);

    /*
     * ArrayHash
     */

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_nullable_arrayhash_attribute',
            'data_type' => 'arrayhash',
            'nullable' => true,
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_required_arrayhash_attribute',
            'data_type' => 'arrayhash',
            'nullable' => false,
        ]);

    $this->assertFileEquals(
        expected: generated_path('requests/RequestWithRules.php'),
        actual: base_path('app/JsonApi/TestApi/Pages/PageRequest.generated.php')
    );
});
