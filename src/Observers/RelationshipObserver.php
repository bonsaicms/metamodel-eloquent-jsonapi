<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Observers;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class RelationshipObserver
{
    public function __construct(
        protected JsonApiManagerContract $manager
    ) {}

    /**
     * Handle the Relationship "created" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function created(Relationship $relationship)
    {
        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.relationship.schema.'.__FUNCTION__)) {
            $this->manager->regenerateSchema($relationship->leftEntity);
            $this->manager->regenerateSchema($relationship->rightEntity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.relationship.request.'.__FUNCTION__)) {
            $this->manager->regenerateRequest($relationship->leftEntity);
            $this->manager->regenerateRequest($relationship->rightEntity);
        }
    }

    /**
     * Handle the Relationship "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function updated(Relationship $relationship)
    {
        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.relationship.schema.'.__FUNCTION__)) {
            $this->manager->regenerateSchema($relationship->leftEntity);
            $this->manager->regenerateSchema($relationship->rightEntity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.relationship.request.'.__FUNCTION__)) {
            $this->manager->regenerateRequest($relationship->leftEntity);
            $this->manager->regenerateRequest($relationship->rightEntity);
        }
    }

    /**
     * Handle the Relationship "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function deleted(Relationship $relationship)
    {
        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.relationship.schema.'.__FUNCTION__)) {
            $this->manager->regenerateSchema($relationship->leftEntity);
            $this->manager->regenerateSchema($relationship->rightEntity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.relationship.request.'.__FUNCTION__)) {
            $this->manager->regenerateRequest($relationship->leftEntity);
            $this->manager->regenerateRequest($relationship->rightEntity);
        }
    }
}
