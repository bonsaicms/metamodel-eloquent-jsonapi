<?php

use BonsaiCms\Metamodel\Models\Entity;

it('creates routes for a new entity', function () {
    Entity::factory()->create([
        'name' => 'BlueDog',
    ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/routes/singleEntity.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php'
    );
});

it('creates routes for two entities', function () {
    Entity::factory()->create([
        'name' => 'BlueDog',
    ]);

    Entity::factory()->create([
        'name' => 'RedCat',
    ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generated/routes/twoEntities.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/routes-custom/api-custom.generated.php'
    );
});
