<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class RegenerateServer extends Command
{
    protected $signature = 'metamodel:regenerate-server';

    protected $description = 'This command will regenerate the server file.';

    public function handle(JsonApiManagerContract $manager)
    {
        $this->info('Regenerating server file...');

        $manager->regenerateServer();

        $this->info('Server file was regenerated successfully.');
    }
}
