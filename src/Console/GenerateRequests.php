<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class GenerateRequests extends Command
{
    protected $signature = 'metamodel:generate-requests';

    protected $description = 'This command will generate a request file for every entity, that has no request file yet.';

    public function handle(JsonApiManagerContract $manager)
    {
        $this->info('Generating request files...');

        $this->withProgressBar(Entity::all(), function ($entity) use ($manager) {
            if (! $manager->requestExists($entity)) {
                $manager->generateRequest($entity);
            }
        });
        $this->info('');
    }
}
