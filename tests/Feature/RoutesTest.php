<?php

use BonsaiCms\Metamodel\Models\Entity;

it('creates routes for a new entity', function () {
    Entity::factory()->create([
        'name' => 'BlueDog',
    ]);

    $this->assertFileEquals(
        expected: generated_path('routes/singleEntity.php'),
        actual: base_path('routes-custom/api-custom.generated.php')
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
        expected: generated_path('routes/twoEntities.php'),
        actual: base_path('routes-custom/api-custom.generated.php')
    );
});
