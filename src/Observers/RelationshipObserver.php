<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Observers;

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
        // TODO
//        $this->manager->regenerateModel($relationship->leftEntity);
//        $this->manager->regenerateModel($relationship->rightEntity);
    }

    /**
     * Handle the Relationship "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function updated(Relationship $relationship)
    {
        // TODO
//        $this->manager->regenerateModel($relationship->leftEntity);
//        $this->manager->regenerateModel($relationship->rightEntity);
    }

    /**
     * Handle the Relationship "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function deleted(Relationship $relationship)
    {
        // TODO
//        $this->manager->regenerateModel($relationship->leftEntity);
//        $this->manager->regenerateModel($relationship->rightEntity);
    }
}
