<?php

return [
    'generate' => [
        'schema' => [
            'folder' => app_path('JsonApi/V1'),
            'namespace' => app()->getNamespace().'JsonApi\\V1',
            'parentClass' => \LaravelJsonApi\Eloquent\Schema::class,
            'fileSuffix' => 'Schema.php',
            'classSuffix' => 'Schema',
        ],
        'request' => [
            'folder' => app_path('JsonApi/V1'),
            'namespace' => app()->getNamespace().'JsonApi\\V1',
            'parentClass' => \LaravelJsonApi\Laravel\Http\Requests\ResourceRequest::class,
            'fileSuffix' => 'Request.php',
            'classSuffix' => 'Request',
        ],
        'routes' => [
            'canBeEmpty' => true,
            'folder' => base_path('routes'),
            'file' => 'api.php',
            'dependencies' => [
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \LaravelJsonApi\Laravel\Http\Controllers\JsonApiController::class,
            ],
            'namespace' => '',
        ],
        'server' => [
            'folder' => app_path('JsonApi/V1'),
            'file' => 'Server.php',
        ],
    ],
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
            'server' => [
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
            'server' => [
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
            'server' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
        ],
    ],
];
