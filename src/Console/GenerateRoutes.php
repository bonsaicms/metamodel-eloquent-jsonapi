<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class GenerateRoutes extends Command
{
    protected $signature = 'metamodel:generate-routes';

    protected $description = 'This command will generate the routes file, if it does not exist yet.';

    public function handle(JsonApiManagerContract $manager)
    {
        $this->info('Generating routes file...');

        if (! $manager->routesExist()) {
            $manager->generateRoutes();
            $this->info('Routes file were generated successfully.');
        } else {
            $this->warn('Routes file already exist.');
        }
    }
}
