<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\MetamodelEloquentJsonApi\Stub;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\Support\Stubs\Actions\SkipEmptyLines;

trait WorksWithRequestRelationshipsRules
{
    /*
     * General methods
     */

    // TODO
    protected function resolveRequestRelationshipsRules(Entity $entity): string
    {
        if ($entity->leftRelationships->isEmpty() && $entity->rightRelationships->isEmpty()) {
            return '            //';
        }

        return app(Pipeline::class)
            ->send(
                $entity->attributes->reduce(function (string $carry, Attribute $attribute) {
                    return $carry .= $this->resolveRelationshipRules($attribute) . PHP_EOL;
                }, '')
            )
            ->through([
                SkipEmptyLines::class,
            ])
            ->thenReturn();
    }

    protected function resolveRequestRelationshipRulesDependencies(Entity $entity): Collection
    {
        // TODO: nejako rozumnejsie getovat vsetky relationshipy ?
        return $entity->leftRelationships
            ->merge($entity->rightRelationships)
            ->map(function (Relationship $relationship) {
                $method = 'resolve'.Str::studly($relationship->cardinality).'RelationshipRulesDependencies';
                return $this->$method($relationship);
            })
            ->flatten();
    }

    protected function resolveRelationshipRules(Attribute $attribute): string
    {
        $method = 'resolve'.Str::studly($attribute->cardinality).'RelationshipRules';

        $rules = [
            $attribute->nullable ? "'nullable'" : "'required'",
            ...$this->$method($attribute),
        ];

        return Stub::make('request/rule', [
            'field' => Str::camel($attribute->column),
            'rules' => collect($rules)->reduce(function (string $carry, string $rule) {
                return $carry .= '                '.$rule.','.PHP_EOL;
            }, ''),
        ]);
    }

    /*
     * OneToOne
     */

    protected function resolveOneToOneRelationshipRules(Relationship $relationship): array
    {
        return [
            "'TODO: OneToOne relationship rules'",
        ];
    }

    protected function resolveOneToOneRelationshipRulesDependencies(Relationship $relationship): array
    {
        return [];
    }
}
