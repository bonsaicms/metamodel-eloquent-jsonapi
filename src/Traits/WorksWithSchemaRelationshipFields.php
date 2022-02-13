<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelEloquentJsonApi\Stub;
use BonsaiCms\Support\Stubs\Actions\SkipEmptyLines;
use BonsaiCms\Support\Stubs\Actions\TrimNewLinesFromTheEnd;

trait WorksWithSchemaRelationshipFields
{
    /*
     * General methods
     */

    protected function resolveSchemaRelationshipFields(Entity $entity): string
    {
        return app(Pipeline::class)
            ->send($this->getSchemaSortedRelationshipsForEntity($entity)
                ->map(fn (Relationship $relationship) =>
                    $this->resolveSchemaRelationshipFieldsForRelationship($entity, $relationship)
                )
                ->join(PHP_EOL)
            )
            ->through([
                SkipEmptyLines::class,
                TrimNewLinesFromTheEnd::class,
            ])
            ->thenReturn();
    }

    protected function resolveSchemaRelationshipFieldsDependencies(Entity $entity): Collection
    {
        return $this->getSchemaSortedRelationshipsForEntity($entity)
            ->map(fn (Relationship $relationship) =>
                $this->resolveSchemaRelationshipFieldDependenciesForRelationship($entity, $relationship)
            )
            ->flatten();
    }

    protected function getSchemaSortedRelationshipsForEntity(Entity $entity): Collection
    {
        return $this->sortSchemaRelationships(
            $entity
                ->leftRelationships
                ->merge(
                    $entity->rightRelationships
                )
        );
    }

    // TODO: more custom method name
    protected function sortSchemaRelationships(Collection $relationships): Collection
    {
        // TODO: implement some sort
        // TODO: DRY

        return $relationships;
    }

    protected function resolveSchemaRelationshipFieldsForRelationship(Entity $entity, Relationship $relationship): string
    {
        if ($entity->is($relationship->leftEntity)) {
            $field = $relationship->left_relationship_name;
            $type = Str::of($relationship->rightEntity->name)->plural()->kebab();

            if ($relationship->cardinality === 'oneToOne') {
                $cardinality = 'hasOne';
            } else if ($relationship->cardinality === 'oneToMany') {
                $cardinality = 'hasMany';
            } else {
                $cardinality = 'belongsToMany';
            }
        } else {
            $field = $relationship->right_relationship_name;
            $type = Str::of($relationship->leftEntity->name)->plural()->kebab();

            if ($relationship->cardinality === 'manyToMany') {
                $cardinality = 'belongsToMany';
            } else {
                $cardinality = 'belongsTo';
            }
        }

        return Stub::make("schema/{$cardinality}Relationship", [
            'field' => $field,
            'type' => $type,
        ]);
    }

    protected function resolveSchemaRelationshipFieldDependenciesForRelationship(Entity $entity, Relationship $relationship): array
    {
        if ($entity->is($relationship->leftEntity)) {
            if ($relationship->cardinality === 'oneToOne') {
                return [ \LaravelJsonApi\Eloquent\Fields\Relations\HasOne::class ];
            } else if ($relationship->cardinality === 'oneToMany') {
                return [ \LaravelJsonApi\Eloquent\Fields\Relations\HasMany::class ];
            } else {
                return [ \LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany::class ];
            }
        } else {
            if ($relationship->cardinality === 'manyToMany') {
                return [ \LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany::class ];
            } else {
                return [ \LaravelJsonApi\Eloquent\Fields\Relations\BelongsTo::class ];
            }
        }
    }

//    /*
//     * String
//     */
//
//    protected function resolveStringRelationshipField(Relationship $relationship): string
//    {
//        $name = Str::camel($relationship->column);
//        return "Str::make('$name')->sortable(),";
//    }
//
//    protected function resolveStringRelationshipFieldDependencies(Relationship $relationship): array
//    {
//        return [ 'LaravelJsonApi\\Eloquent\\Fields\\Str' ];
//    }
}
