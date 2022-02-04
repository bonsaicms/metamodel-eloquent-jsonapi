<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Observers;

use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\MetamodelEloquentJsonApi\Contacts\JsonApiManagerContract;

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
        // TODO
//        $this->manager->regenerateModel($attribute->entity);
    }

    /**
     * Handle the Attribute "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function updated(Attribute $attribute)
    {
        // TODO
//        $this->manager->regenerateModel($attribute->entity);
    }

    /**
     * Handle the Attribute "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function deleted(Attribute $attribute)
    {
        // TODO
//        $this->manager->regenerateModel($attribute->entity);
    }
}
