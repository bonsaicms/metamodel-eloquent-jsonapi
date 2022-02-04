<?php

namespace BonsaiCms\MetamodelEloquentJsonApi;

use Illuminate\Support\ServiceProvider;

class MetamodelEloquentJsonApiServiceProvider extends ServiceProvider
{
    /**
     * Register package.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/bonsaicms-metamodel-eloquent-jsonapi.php', 'bonsaicms-metamodel-eloquent-jsonapi'
        );
    }

    /**
     * Bootstrap package.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->publishes([
            __DIR__.'/../config/bonsaicms-metamodel-eloquent-jsonapi.php' => $this->app->configPath('bonsaicms-metamodel-eloquent-jsonapi.php'),
        ], 'bonsaicms-metamodel-eloquent-jsonapi-config');
    }
}
