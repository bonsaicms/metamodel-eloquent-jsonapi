<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class RegenerateRoutes extends Command
{
    protected $signature = 'metamodel:regenerate-routes';

    protected $description = 'This command will regenerate the routes file.';

    public function handle(JsonApiManagerContract $manager)
    {
        $this->info('Regenerating routes file...');

        $manager->regenerateRoutes();

        $this->info('Routes file was regenerated successfully.');
    }
}
