<?php

use Illuminate\Support\Facades\Config;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;

Route::group(Config::get('bonsaicms-metamodel-eloquent-jsonapi.routesConfig'), function () {
    JsonApiRoute::server(Config::get('bonsaicms-metamodel-eloquent-jsonapi.server'))
        ->prefix(Config::get('bonsaicms-metamodel-eloquent-jsonapi.baseUri'))
        ->resources(function ($server) {
//        $server->resource('entities', JsonApiController::class)
//            ->relationships(function ($relationships) {
//                $relationships->hasMany('attributes');
//                $relationships->hasMany('leftRelationships');
//                $relationships->hasMany('rightRelationships');
//            });
//        $server->resource('attributes', JsonApiController::class)
//            ->relationships(function ($relationships) {
//                $relationships->hasOne('entity');
//            });
//        $server->resource('relationships', JsonApiController::class)
//            ->relationships(function ($relationships) {
//                $relationships->hasOne('leftEntity');
//                $relationships->hasOne('rightEntity');
//            });
        });
});
