<?php

return [
//    /*
//     * The name of the server.
//     */
//    'server' => 'metamodel-eloquent',
//
//    /*
//     * Determine if the server is authorizable.
//     */
//    'authorizable' => true,
//
//    /*
//     * The base URI namespace for the server.
//     */
//    'baseUri' => '/api/metamodel-eloquent',
//
//    /*
//     * This array will be passed as the first argument to Route::group()
//     */
//    'routesConfig' => [
//        'middleware' => [
//            'api',
//            'auth',
//        ],
//    ],

    'bind' => [
        'jsonApiManager' => true,
    ],
    'observeModels' => [
        'entity' => [
            'enabled' => true,
            'schema' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
            'request' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
            'routes' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
        ],
        'attribute' => [
            'enabled' => true,
            'schema' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
            'request' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
            'routes' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
        ],
        'relationship' => [
            'enabled' => true,
            'schema' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
            'request' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
            'routes' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
        ],
    ],
    'generate' => [
        'schema' => [
            'folder' => app_path('JsonApi/Generated'),
            'namespace' => app()->getNamespace().'JsonApi\\Generated',
            'parentModel' => LaravelJsonApi\Eloquent\Schema::class,
            'fileSuffix' => 'Schema.php',
            'classSuffix' => 'Schema',
        ],
        'request' => [
            'folder' => app_path('JsonApi/Generated'),
            'namespace' => app()->getNamespace().'JsonApi\\Generated',
            'parentModel' => LaravelJsonApi\Laravel\Http\Requests\ResourceRequest::class,
            'fileSuffix' => 'Request.php',
            'classSuffix' => 'Request',
        ],
        'routes' => [
            'folder' => base_path('routes'),
            'file' => 'api.php',
            'dependencies' => [
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \LaravelJsonApi\Laravel\Http\Controllers\JsonApiController::class,
            ],
            'namespace' => '',
        ],
    ],
];
