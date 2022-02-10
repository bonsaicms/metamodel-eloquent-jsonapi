<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Relationship;

beforeEach(function () {
    $this->blueDog = Entity::factory()
        ->create([
            'name' => 'BlueDog',
            'table' => 'blue_dog_table',
        ]);

    $this->redCat = Entity::factory()
        ->create([
            'name' => 'RedCat',
            'table' => 'red_cat_table',
        ]);
});

it('creates a requests for two entities in oneToOne relationship with corresponding validation rules', function () {
    Relationship::factory()
        ->for($this->blueDog, 'leftEntity')
        ->for($this->redCat, 'rightEntity')
        ->create([
            'cardinality' => 'oneToOne',
            'right_foreign_key' => 'blue_dog_id',
            'left_relationship_name' => 'redCat',
            'right_relationship_name' => 'blueDog',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/requests/OneToOneBlueDogRequest.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/BlueDogs/BlueDogRequest.generated.php'
    );
    $this->assertFileEquals(
        expected: __DIR__.'/../generated/requests/OneToOneRedCatRequest.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/RedCats/RedCatRequest.generated.php'
    );
});

it('creates a requests for two entities in oneToMany relationship with corresponding validation rules', function () {
    Relationship::factory()
        ->for($this->blueDog, 'leftEntity')
        ->for($this->redCat, 'rightEntity')
        ->create([
            'cardinality' => 'oneToMany',
            'right_foreign_key' => 'blue_dog_id',
            'left_relationship_name' => 'redCats',
            'right_relationship_name' => 'blueDog',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/requests/OneToManyBlueDogRequest.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/BlueDogs/BlueDogRequest.generated.php'
    );
    $this->assertFileEquals(
        expected: __DIR__.'/../generated/requests/OneToManyRedCatRequest.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/RedCats/RedCatRequest.generated.php'
    );
});

it('creates a requests for two entities in manyToMany relationship with corresponding validation rules', function () {
    Relationship::factory()
        ->for($this->blueDog, 'leftEntity')
        ->for($this->redCat, 'rightEntity')
        ->create([
            'cardinality' => 'manyToMany',
            'pivot_table' => 'blue_dog_red_cat',
            'left_foreign_key' => 'blue_dog_id',
            'right_foreign_key' => 'red_cat_id',
            'left_relationship_name' => 'redCats',
            'right_relationship_name' => 'blueDogs',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/requests/ManyToManyBlueDogRequest.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/BlueDogs/BlueDogRequest.generated.php'
    );
    $this->assertFileEquals(
        expected: __DIR__.'/../generated/requests/ManyToManyRedCatRequest.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/JsonApi/TestApi/RedCats/RedCatRequest.generated.php'
    );
});
