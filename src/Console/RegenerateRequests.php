<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class RegenerateRequests extends Command
{
    protected $signature = 'metamodel:regenerate-requests';

    protected $description = 'This command will regenerate a request for every entity.';

    public function handle(JsonApiManagerContract $manager)
    {
        $this->info('Regenerating requests...');

        $this->withProgressBar(Entity::all(), function ($entity) use ($manager) {
            $manager->regenerateRequest($entity);
        });
        $this->info('');
    }
}
