<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class DeleteSchemas extends Command
{
    protected $signature = 'metamodel:delete-schemas';

    protected $description = 'This command will delete all existing schema files for all entities.';

    public function handle(JsonApiManagerContract $manager)
    {
        $this->info('Deleting schema files...');

        $this->withProgressBar(Entity::all(), function ($entity) use ($manager) {
            $manager->deleteSchema($entity);
        });
        $this->info('');
    }
}
