<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class DeleteServer extends Command
{
    protected $signature = 'metamodel:delete-server';

    protected $description = 'This command will delete the server file, if it exists.';

    public function handle(JsonApiManagerContract $manager)
    {
        $this->info('Deleting server file...');

        if ($manager->serverExists()) {
            $manager->deleteServer();
            $this->info('Server file was deleted successfully.');
        } else {
            $this->warn('Server file does not exist, therefore it could not be deleted.');
        }
    }
}
