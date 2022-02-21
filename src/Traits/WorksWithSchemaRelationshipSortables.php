<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Collection;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelEloquentJsonApi\Stub;
use BonsaiCms\Support\Stubs\Actions\SkipEmptyLines;

trait WorksWithSchemaRelationshipSortables
{
    protected function resolveSchemaSortablesMethod(Entity $entity): string
    {
        $sortables = $this->resolveSchemaRelationshipSortables($entity);

        return ($sortables->isEmpty())
            ? ''
            : Stub::make(
                'schema/sortablesMethod',
                [
                    'sortables' => $this
                        ->resolveSchemaRelationshipSortables($entity)
                        ->join(PHP_EOL),
                ],
                [
                    SkipEmptyLines::class,
                ],
            );
    }

    protected function resolveSchemaRelationshipSortables(Entity $entity): Collection
    {
        return $this
            ->getSchemaSortedSortableRelationshipsForEntity($entity)
            ->map(fn (Relationship $relationship) =>
                $this->resolveSchemaRelationshipSortablesForRelationship($entity, $relationship)
            );
    }

    protected function resolveSchemaRelationshipSortablesForRelationship(Entity $entity, Relationship $relationship): string
    {
        return Stub::make('schema/sortCountable', [
            'field' => ($entity->is($relationship->leftEntity))
                ? $relationship->left_relationship_name
                : $relationship->right_relationship_name,
        ]);
    }

    protected function resolveSchemaRelationshipSortablesDependencies(Entity $entity): Collection
    {
        return $this->getSchemaSortedSortableRelationshipsForEntity($entity)
            ->map(fn (Relationship $relationship) =>
                $this->resolveSchemaRelationshipSortableDependenciesForRelationship($entity, $relationship)
            )
            ->flatten();
    }

    protected function getSchemaSortedSortableRelationshipsForEntity(Entity $entity): Collection
    {
        return $this->sortSchemaSortableRelationships(
            $entity
                ->leftRelationships
                ->filter(static fn (Relationship $relationship)
                    => in_array($relationship->cardinality, ['oneToMany', 'manyToMany'])
                )
                ->merge(
                    $entity
                        ->rightRelationships
                        ->filter(static fn (Relationship $relationship)
                            => $relationship->cardinality === 'manyToMany'
                        )
                )
        );
    }

    // TODO: more custom method name
    protected function sortSchemaSortableRelationships(Collection $relationships): Collection
    {
        // TODO: implement some sort
        // TODO: DRY

        return $relationships;
    }

    protected function resolveSchemaRelationshipSortableDependenciesForRelationship(Entity $entity, Relationship $relationship): array
    {
        return (
            ($relationship->cardinality === 'manyToMany') ||
            ($relationship->cardinality === 'oneToMany' && $entity->is($relationship->leftEntity))
        )
            ? [
                \LaravelJsonApi\Contracts\Schema\Sortable::class,
                \LaravelJsonApi\Eloquent\Sorting\SortCountable::class,
            ]
            : [];
    }
}
