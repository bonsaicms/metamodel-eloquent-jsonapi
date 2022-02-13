<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class GenerateServer extends Command
{
    protected $signature = 'metamodel:generate-server';

    protected $description = 'This command will generate the server file, if it does not exist yet.';

    public function handle(JsonApiManagerContract $manager)
    {
        $this->info('Generating server file...');

        if (! $manager->serverExists()) {
            $manager->generateServer();
            $this->info('Server file was generated successfully.');
        } else {
            $this->warn('Server file already exists.');
        }
    }
}
