<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Observers;

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
        // TODO
//        $this->manager->regenerateModel($entity);
    }

    /**
     * Handle the Entity "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function updated(Entity $entity)
    {
        // TODO
//        $this->manager->regenerateModel($entity);
    }

    /**
     * Handle the Entity "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function deleted(Entity $entity)
    {
        // TODO
//        $this->manager->regenerateModel($entity);
    }
}
