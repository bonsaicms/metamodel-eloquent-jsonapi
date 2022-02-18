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

it('creates routes for two entities with oneToOne relationships', function () {
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
        expected: __DIR__.'/../generated/routes/twoEntitiesWithOneToOneRelationship.php',
        actual: base_path('routes-custom/api-custom.generated.php')
    );
});

it('creates routes for two entities with oneToMany relationships', function () {
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
        expected: __DIR__.'/../generated/routes/twoEntitiesWithOneToManyRelationship.php',
        actual: base_path('routes-custom/api-custom.generated.php')
    );
});

it('creates routes for two entities with manyToMany relationships', function () {
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
        expected: __DIR__.'/../generated/routes/twoEntitiesWithManyToManyRelationship.php',
        actual: base_path('routes-custom/api-custom.generated.php')
    );
});
