<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Observers;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class EntityObserver
{
    public function __construct(
        protected JsonApiManagerContract $manager
    ) {}

    /**
     * Handle the Entity "created" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function created(Entity $entity)
    {
        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.entity.schema.'.__FUNCTION__)) {
            $this->manager->regenerateSchema($entity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.entity.request.'.__FUNCTION__)) {
            $this->manager->regenerateRequest($entity);
        }
    }

    /**
     * Handle the Entity "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function updated(Entity $entity)
    {
        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.entity.schema.'.__FUNCTION__)) {
            $this->manager->regenerateSchema($entity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.entity.request.'.__FUNCTION__)) {
            $this->manager->regenerateRequest($entity);
        }
    }

    /**
     * Handle the Entity "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function deleted(Entity $entity)
    {
        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.entity.schema.'.__FUNCTION__)) {
            $this->manager->regenerateSchema($entity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.entity.request.'.__FUNCTION__)) {
            $this->manager->regenerateRequest($entity);
        }
    }
}
