<?php

namespace BonsaiCms\MetamodelEloquentJsonApi;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\ServiceProvider;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelEloquentJsonApi\Observers\EntityObserver;
use BonsaiCms\MetamodelEloquentJsonApi\Observers\AttributeObserver;
use BonsaiCms\MetamodelEloquentJsonApi\Observers\RelationshipObserver;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class MetamodelEloquentJsonApiServiceProvider extends ServiceProvider
{
    /**
     * Register package.
     *
     * @return void
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/bonsaicms-metamodel-eloquent-jsonapi.php',
            'bonsaicms-metamodel-eloquent-jsonapi'
        );

        // Bind implementation
        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.bind.jsonApiManager')) {
            $this->app->singleton(JsonApiManagerContract::class, JsonApiManager::class);
        }
    }

    /**
     * Bootstrap package.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/bonsaicms-metamodel-eloquent-jsonapi.php' => $this->app->configPath('bonsaicms-metamodel-eloquent-jsonapi.php'),
        ], 'bonsaicms-metamodel-eloquent-jsonapi-config');

        // Publish stubs
        $this->publishes([
            __DIR__.'/../resources/stubs/' => $this->app->resourcePath('stubs/bonsaicms/metamodel-eloquent-jsonapi/'),
        ], 'bonsaicms-metamodel-eloquent-jsonapi-stubs');

        // Observe models
        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.entity.enabled')) {
            Entity::observe(EntityObserver::class);
        }

        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.attribute.enabled')) {
            Attribute::observe(AttributeObserver::class);
        }

        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.relationship.enabled')) {
            Relationship::observe(RelationshipObserver::class);
        }
    }
}
