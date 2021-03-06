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
            'generate' => [
                'models' => [
                    'folder' => app_path('Models'),
                    'fileSuffix' => '.generated.php',
                    'namespace' => 'TestApp\\Models',
                    'parentClass' => 'Some\\Namespace\\ParentClass',
                ],
                'policies' => [
                    'folder' => app_path('Policies'),
                    'fileSuffix' => 'Policy.generated.php',
                    'namespace' => 'TestApp\\Policies',
                    'parentClass' => null,
                ],
            ],
            'bind' => [
                'modelManager' => true,
            ],
            'observeModels' => [
                'entity' => [
                    'enabled' => true,
                    'model' => [
                        'created' => true,
                        'updated' => true,
                        'deleted' => true,
                    ],
                    'policy' => [
                        'created' => true,
                        'updated' => true,
                        'deleted' => true,
                    ],
                ],
                'attribute' => [
                    'enabled' => true,
                    'model' => [
                        'created' => true,
                        'updated' => true,
                        'deleted' => true,
                    ],
                    'policy' => [
                        'created' => true,
                        'updated' => true,
                        'deleted' => true,
                    ],
                ],
                'relationship' => [
                    'enabled' => true,
                    'model' => [
                        'created' => true,
                        'updated' => true,
                        'deleted' => true,
                    ],
                    'policy' => [
                        'created' => true,
                        'updated' => true,
                        'deleted' => true,
                    ],
                ],
            ],
        ]);
        config()->set('bonsaicms-metamodel-eloquent-jsonapi.generate', [
            'schema' => [
                'folder' => app_path('JsonApi/TestApi'),
                'namespace' => app()->getNamespace().'JsonApi\\TestApi',
                'parentClass' => \Testing\My\Custom\AbstractSchema::class,
                'fileSuffix' => 'Schema.generated.php',
                'classSuffix' => 'CustomSchemaClassSuffix',
                'dependencies' => [
                    \LaravelJsonApi\Eloquent\Fields\ID::class,
                    \LaravelJsonApi\Eloquent\Fields\DateTime::class,
                    \LaravelJsonApi\Eloquent\Filters\WhereIdIn::class,
                    \LaravelJsonApi\Eloquent\Filters\WhereIdNotIn::class,
                    \LaravelJsonApi\Eloquent\Contracts\Paginator::class,
                    \LaravelJsonApi\Eloquent\Pagination\PagePagination::class,
                ],
            ],
            'request' => [
                'folder' => app_path('JsonApi/TestApi'),
                'namespace' => app()->getNamespace().'JsonApi\\TestApi',
                'parentClass' => \Testing\My\Custom\AbstractRequest::class,
                'fileSuffix' => 'Request.generated.php',
                'classSuffix' => 'CustomRequestClassSuffix',
            ],
            'routes' => [
                'canBeEmpty' => false,
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
             app_path('JsonApi')
        );

        File::deleteDirectory(
             base_path('routes-custom')
        );
    }
}
