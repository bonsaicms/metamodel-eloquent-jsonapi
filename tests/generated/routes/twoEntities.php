<?php

use CustomClosure;
use My\Custom\SubstituteBindings;
use My\Custom\CompletelyCustomClass;
use My\Custom\Something\JsonApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

JsonApiRoute::server('v1')
    ->prefix('jsonapi')
    ->namespace('Api\V1')
    ->withoutMiddleware(SubstituteBindings::class)
    ->resources(function ($server) {
        $server->resource('blue-dogs', '\\' . JsonApiController::class);
        $server->resource('red-cats', '\\' . JsonApiController::class);
    });
