<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Tests;

use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;
use LaravelJsonApi\Testing\MakesJsonApiRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends Orchestra
{
    use RefreshDatabase;
    use MakesJsonApiRequests;

    protected function getPackageProviders($app)
    {
        return [
            \LaravelJsonApi\Laravel\ServiceProvider::class,
            \LaravelJsonApi\Encoder\Neomerx\ServiceProvider::class,
            \LaravelJsonApi\Spec\ServiceProvider::class,
            \LaravelJsonApi\Validation\ServiceProvider::class,
            \BonsaiCms\Metamodel\MetamodelServiceProvider::class,
            \BonsaiCms\MetamodelEloquentJsonApi\MetamodelEloquentJsonApiServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'pgsql',
            'url' => null,
            'host' => '127.0.0.1',
            'port' => '5432',
            'database' => 'testing',
            'username' => 'postgres',
            'password' => 'postgres',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ]);
        config()->set('bonsaicms-metamodel', [
            'entityTableName' => 'pre_met_entities_suf_met',
            'attributeTableName' => 'pre_met_attributes_suf_met',
            'relationshipTableName' => 'pre_met_relationships_suf_met',

            'generatedTablePrefix' => 'pre_gen_',
            'generatedTableSuffix' => '_suf_gen',
        ]);
        config()->set('bonsaicms-metamodel-jsonapi', [
            'server' => 'testServerName',
            'authorizable' => false,
            'baseUri' => '/api/testUrlPrefix',
            'routesConfig' => [],
        ]);
        config()->set('bonsaicms-metamodel-eloquent', [
            'bind' => [
                'modelManager' => true,
            ],
            'observeModels' => [
                'entity' => true,
                'attribute' => true,
                'relationship' => true,
            ],
            'generate' => [
                'folder' => __DIR__.'/../vendor/orchestra/testbench-core/laravel/app/Models',
                'modelFileSuffix' => '.generated.php',
                'namespace' => 'TestApp\\Models',
                'parentModel' => 'Some\\Namespace\\ParentModel',
            ],
        ]);
        config()->set('bonsaicms-metamodel-eloquent-jsonapi.generate', [
            'schema' => [
                'folder' => app_path('JsonApi/TestApi'),
                'namespace' => app()->getNamespace().'JsonApi\\TestApi',
                'parentModel' => \Testing\My\Custom\AbstractSchema::class,
                'fileSuffix' => 'Schema.generated.php',
                'classSuffix' => 'CustomSchemaClassSuffix',
            ],
            'request' => [
                'folder' => app_path('JsonApi/TestApi'),
                'namespace' => app()->getNamespace().'JsonApi\\TestApi',
                'parentModel' => \Testing\My\Custom\AbstractRequest::class,
                'fileSuffix' => 'Request.generated.php',
                'classSuffix' => 'CustomRequestClassSuffix',
            ],
            'routes' => [
                'folder' => base_path('routes-custom'),
                'file' => 'api-custom.generated.php',
                'dependencies' => [
                    \CustomClosure::class,
                    \My\Custom\SubstituteBindings::class,
                    \My\Custom\CompletelyCustomClass::class,
                    \My\Custom\Something\JsonApiController::class,
                ],
                'namespace' => '', // TODO: toto by som mal aj prepisovat do toho suboru asi, ci?
            ],
            'server' => [
                'folder' => app_path('JsonApi/TestApi'),
                'file' => 'TestServer.generated.php',
            ],
        ]);
    }

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->deleteGeneratedFiles();
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->deleteGeneratedFiles();
    }

    protected function deleteGeneratedFiles()
    {
        File::deleteDirectory(
            __DIR__.'/../vendor/orchestra/testbench-core/laravel/app/JsonApi'
        );

        File::deleteDirectory(
            __DIR__.'/../vendor/orchestra/testbench-core/laravel/routes-custom'
        );
    }
}
