<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\MetamodelEloquentJsonApi\Stub;
use BonsaiCms\Support\Stubs\Actions\SkipEmptyLines;

trait WorksWithRequestAttributesRules
{
    /*
     * General methods
     */

    protected function resolveRequestAttributesRules(Entity $entity): string
    {
        if ($entity->attributes->isEmpty()) {
            return '            //';
        }

        return app(Pipeline::class)
            ->send(
                $entity->attributes->reduce(function (string $carry, Attribute $attribute) {
                    return $carry .= $this->resolveAttributeRules($attribute) . PHP_EOL;
                }, '')
            )
            ->through([
                SkipEmptyLines::class,
            ])
            ->thenReturn();
    }

    protected function resolveRequestAttributeRulesDependencies(Entity $entity): Collection
    {
        return $entity->attributes
            ->map(function (Attribute $attribute) {
                $method = "resolve{$attribute->data_type}AttributeRulesDependencies";
                return $this->$method($attribute);
            })
            ->flatten();
    }

    protected function resolveAttributeRules(Attribute $attribute): string
    {
        $method = "resolve{$attribute->data_type}AttributeRules";

        $rules = [
            $attribute->nullable
                ? "'nullable'"
                : (
                    ($attribute->data_type === 'boolean')
                        ? "'present'"
                        : "'required'"
            ),
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
     * String
     */

    protected function resolveStringAttributeRules(Attribute $attribute): array
    {
        return [
            "'string'",
        ];
    }

    protected function resolveStringAttributeRulesDependencies(Attribute $attribute): array
    {
        return [];
    }

    /*
     * Text
     */

    protected function resolveTextAttributeRules(Attribute $attribute): array
    {
        return [
            "'string'",
        ];
    }

    protected function resolveTextAttributeRulesDependencies(Attribute $attribute): array
    {
        return [];
    }

    /*
     * Boolean
     */

    protected function resolveBooleanAttributeRules(Attribute $attribute): array
    {
        return [
            "'boolean'",
        ];
    }

    protected function resolveBooleanAttributeRulesDependencies(Attribute $attribute): array
    {
        return [];
    }

    /*
     * Integer
     */

    protected function resolveIntegerAttributeRules(Attribute $attribute): array
    {
        return [
            "'integer'",
        ];
    }

    protected function resolveIntegerAttributeRulesDependencies(Attribute $attribute): array
    {
        return [];
    }

    /*
     * Date
     */

    protected function resolveDateAttributeRules(Attribute $attribute): array
    {
        return [
            "'date'",
        ];
    }

    protected function resolveDateAttributeRulesDependencies(Attribute $attribute): array
    {
        return [];
    }

    /*
     * Time
     */

    protected function resolveTimeAttributeRules(Attribute $attribute): array
    {
        return [
            "'date_format:H:i'",
        ];
    }

    protected function resolveTimeAttributeRulesDependencies(Attribute $attribute): array
    {
        return [];
    }

    /*
     * DateTime
     */

    protected function resolveDateTimeAttributeRules(Attribute $attribute): array
    {
        return [
            "'date'",
        ];
    }

    protected function resolveDateTimeAttributeRulesDependencies(Attribute $attribute): array
    {
        return [];
    }

    /*
     * ArrayList
     */

    protected function resolveArrayListAttributeRules(Attribute $attribute): array
    {
        return [
            "'json'",
        ];
    }

    protected function resolveArrayListAttributeRulesDependencies(Attribute $attribute): array
    {
        return [];
    }

    /*
     * ArrayHash
     */

    protected function resolveArrayHashAttributeRules(Attribute $attribute): array
    {
        return [
            "'json'",
        ];
    }

    protected function resolveArrayHashAttributeRulesDependencies(Attribute $attribute): array
    {
        return [];
    }
}
