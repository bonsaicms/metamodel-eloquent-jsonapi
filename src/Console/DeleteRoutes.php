<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class DeleteRoutes extends Command
{
    protected $signature = 'metamodel:delete-routes';

    protected $description = 'This command will delete the routes file, if it exists.';

    public function handle(JsonApiManagerContract $manager)
    {
        $this->info('Deleting routes file...');

        if ($manager->routesExist()) {
            $manager->deleteRoutes();
            $this->info('Routes file was deleted successfully.');
        } else {
            $this->warn('Routes file does not exist, therefore it could not be deleted.');
        }
    }
}
