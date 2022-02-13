<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class GenerateSchemas extends Command
{
    protected $signature = 'metamodel:generate-schemas';

    protected $description = 'This command will generate a schema file for every entity, that has no schema file yet.';

    public function handle(JsonApiManagerContract $manager)
    {
        $this->info('Generating schema files...');

        $this->withProgressBar(Entity::all(), function ($entity) use ($manager) {
            if (! $manager->schemaExists($entity)) {
                $manager->generateSchema($entity);
            }
        });
        $this->info('');
    }
}
