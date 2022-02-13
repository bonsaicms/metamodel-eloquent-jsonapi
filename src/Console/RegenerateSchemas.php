<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class RegenerateSchemas extends Command
{
    protected $signature = 'metamodel:regenerate-schemas';

    protected $description = 'This command will regenerate a schema for every entity.';

    public function handle(JsonApiManagerContract $manager)
    {
        $this->info('Regenerating schemas...');

        $this->withProgressBar(Entity::all(), function ($entity) use ($manager) {
            $manager->regenerateSchema($entity);
        });
        $this->info('');
    }
}
