<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class DeleteRequests extends Command
{
    protected $signature = 'metamodel:delete-requests';

    protected $description = 'This command will delete all existing request files for all entities.';

    public function handle(JsonApiManagerContract $manager)
    {
        $this->info('Deleting request files...');

        $this->withProgressBar(Entity::all(), function ($entity) use ($manager) {
            $manager->deleteRequest($entity);
        });
        $this->info('');
    }
}
