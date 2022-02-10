<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Stub;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\Support\Stubs\Actions\SkipEmptyLines;
use BonsaiCms\Support\Stubs\Actions\TrimNewLinesFromTheEnd;

trait WorksWithRequestRelationshipsRules
{
    /*
     * General methods
     */

    protected function resolveRequestRelationshipsRules(Entity $entity): string
    {
        if ($entity->leftRelationships->isEmpty() && $entity->rightRelationships->isEmpty()) {
            return '            //';
        }

        return app(Pipeline::class)
            ->send($this
                ->getRequestSortedRelationshipsForEntity($entity)
                ->map(
                    fn ($relationship) => $this->resolveRequestRelationshipRules($entity, $relationship)
                )
                ->join(PHP_EOL)
            )
            ->through([
                SkipEmptyLines::class,
                TrimNewLinesFromTheEnd::class,
            ])
            ->thenReturn();
    }

    protected function getRequestSortedRelationshipsForEntity(Entity $entity): Collection
    {
        return $this->sortRequestRelationships(
            $entity
                ->leftRelationships
                ->merge(
                    $entity->rightRelationships
                )
        );
    }

    protected function resolveRequestRelationshipRules(Entity $entity, Relationship $relationship): string
    {
        $method = 'resolveRequest'.Str::studly($relationship->cardinality).'RelationshipRules';

        $rules = [
            // TODO
//            $relationship->nullable ? "'nullable'" : "'required'",
            ...$this->$method($entity, $relationship),
        ];

        return Stub::make('request/rule', [
            'field' => $this->resolveRequestRelationshipRuleFieldName($entity, $relationship),
            'rules' => collect($rules)->map(
                static fn ($rule) => "                $rule,"
            )->join(PHP_EOL)
        ]);
    }

    protected function resolveRequestRelationshipRuleFieldName(Entity $entity, Relationship $relationship): string
    {
        $name = $entity->is($relationship->leftEntity)
            ? 'left'
            : 'right';

        $name .= '_relationship_name';

        return Str::camel($relationship->$name);
    }

    protected function resolveRequestRelationshipRulesDependencies(Entity $entity): Collection
    {
        return $this
            ->getRequestSortedRelationshipsForEntity($entity)
            ->map(function (Relationship $relationship) use ($entity) {
                $method = 'resolveRequest'
                    .Str::studly($relationship->cardinality)
                    .'RelationshipRulesDependencies';
                return $this->$method($entity, $relationship);
            })
            ->flatten();
    }

    // TODO: more custom method name
    protected function sortRequestRelationships(Collection $relationships): Collection
    {
        // TODO: implement some sort
        // TODO: DRY

        return $relationships;
    }

    /*
     * OneToOne
     */

    protected function resolveRequestOneToOneRelationshipRules(Entity $entity, Relationship $relationship): array
    {
        return [
            'JsonApiRule::toOne()',
        ];
    }

    protected function resolveRequestOneToOneRelationshipRulesDependencies(Entity $entity, Relationship $relationship): array
    {
        return [ 'LaravelJsonApi\\Validation\\Rule as JsonApiRule' ];
    }

    /*
     * OneToMany
     */

    protected function resolveRequestOneToManyRelationshipRules(Entity $entity, Relationship $relationship): array
    {
        return [
            $entity->is($relationship->leftEntity)
                ? 'JsonApiRule::toMany()'
                : 'JsonApiRule::toOne()',
        ];
    }

    protected function resolveRequestOneToManyRelationshipRulesDependencies(Entity $entity, Relationship $relationship): array
    {
        return [ 'LaravelJsonApi\\Validation\\Rule as JsonApiRule' ];
    }

    /*
     * ManyToMany
     */

    protected function resolveRequestManyToManyRelationshipRules(Entity $entity, Relationship $relationship): array
    {
        return [
            'JsonApiRule::toMany()',
        ];
    }

    protected function resolveRequestManyToManyRelationshipRulesDependencies(Entity $entity, Relationship $relationship): array
    {
        return [ 'LaravelJsonApi\\Validation\\Rule as JsonApiRule' ];
    }
}
