<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Observers;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class AttributeObserver
{
    public function __construct(
        protected JsonApiManagerContract $manager
    ) {}

    /**
     * Handle the Attribute "created" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function created(Attribute $attribute)
    {
        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.attribute.schema.'.__FUNCTION__)) {
            $this->manager->regenerateSchema($attribute->entity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.attribute.request.'.__FUNCTION__)) {
            $this->manager->regenerateRequest($attribute->entity);
        }
    }

    /**
     * Handle the Attribute "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function updated(Attribute $attribute)
    {
        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.attribute.schema.'.__FUNCTION__)) {
            $this->manager->regenerateSchema($attribute->entity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.attribute.request.'.__FUNCTION__)) {
            $this->manager->regenerateRequest($attribute->entity);
        }
    }

    /**
     * Handle the Attribute "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function deleted(Attribute $attribute)
    {
        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.attribute.schema.'.__FUNCTION__)) {
            $this->manager->regenerateSchema($attribute->entity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent-jsonapi.observeModels.attribute.request.'.__FUNCTION__)) {
            $this->manager->regenerateRequest($attribute->entity);
        }
    }
}
