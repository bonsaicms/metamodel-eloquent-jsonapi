<?php

return [
    /*
     * The name of the server.
     */
    'server' => 'metamodel-eloquent',

    /*
     * Determine if the server is authorizable.
     */
    'authorizable' => true,

    /*
     * The base URI namespace for the server.
     */
    'baseUri' => '/api/metamodel-eloquent',

    'bind' => [
        'jsonApiManager' => true,
    ],
    'observeModels' => [
        'entity' => true,
        'attribute' => true,
        'relationship' => true,

        // TODO: use this syntax
//        'entity' => [
//            'created' => true,
//            'updated' => true,
//            'deleted' => true,
//        ],
//        'attribute' => [
//            'created' => true,
//            'updated' => true,
//            'deleted' => true,
//        ],
//        'relationship' => [
//            'created' => true,
//            'updated' => true,
//            'deleted' => true,
//        ],
    ],
];
