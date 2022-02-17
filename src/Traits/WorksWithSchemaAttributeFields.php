<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;

trait WorksWithSchemaAttributeFields
{
    /*
     * General methods
     */

    protected function resolveSchemaAttributeFields(Entity $entity): string
    {
        return $entity->attributes->reduce(function (string $carry, Attribute $attribute) {
            return $carry .= $this->resolveAttributeField($attribute) . PHP_EOL;
        }, '');
    }

    protected function resolveSchemaAttributeFieldsDependencies(Entity $entity): Collection
    {
        return $entity->attributes
            ->map(function (Attribute $attribute) {
                $method = "resolve{$attribute->data_type}AttributeFieldDependencies";
                return $this->$method($attribute);
            })
            ->flatten();
    }

    protected function resolveAttributeField(Attribute $attribute): string
    {
        $method = "resolve{$attribute->data_type}AttributeField";

        return $this->$method($attribute).PHP_EOL;
    }

    /*
     * String
     */

    protected function resolveStringAttributeField(Attribute $attribute): string
    {
        $name = Str::camel($attribute->column);
        return "Str::make('$name')->sortable(),";
    }

    protected function resolveStringAttributeFieldDependencies(Attribute $attribute): array
    {
        return [ 'LaravelJsonApi\\Eloquent\\Fields\\Str' ];
    }

    /*
     * Text
     */

    protected function resolveTextAttributeField(Attribute $attribute): string
    {
        $name = Str::camel($attribute->column);
        return "Str::make('$name')->sortable(),";
    }

    protected function resolveTextAttributeFieldDependencies(Attribute $attribute): array
    {
        return [ 'LaravelJsonApi\\Eloquent\\Fields\\Str' ];
    }

    /*
     * Boolean
     */

    protected function resolveBooleanAttributeField(Attribute $attribute): string
    {
        $name = Str::camel($attribute->column);
        return "Boolean::make('$name')->sortable(),";
    }

    protected function resolveBooleanAttributeFieldDependencies(Attribute $attribute): array
    {
        return [ 'LaravelJsonApi\\Eloquent\\Fields\\Boolean' ];
    }

    /*
     * Integer
     */

    protected function resolveIntegerAttributeField(Attribute $attribute): string
    {
        $name = Str::camel($attribute->column);
        return "Number::make('$name')->sortable(),";
    }

    protected function resolveIntegerAttributeFieldDependencies(Attribute $attribute): array
    {
        return [ 'LaravelJsonApi\\Eloquent\\Fields\\Number' ];
    }

    /*
     * Date
     */

    protected function resolveDateAttributeField(Attribute $attribute): string
    {
        $name = Str::camel($attribute->column);
        return "DateTime::make('$name')->sortable(),";
    }

    protected function resolveDateAttributeFieldDependencies(Attribute $attribute): array
    {
        return [ 'LaravelJsonApi\\Eloquent\\Fields\\DateTime' ];
    }

    /*
     * Time
     */

    protected function resolveTimeAttributeField(Attribute $attribute): string
    {
        $name = Str::camel($attribute->column);
        return "DateTime::make('$name')->sortable(),";
    }

    protected function resolveTimeAttributeFieldDependencies(Attribute $attribute): array
    {
        return [ 'LaravelJsonApi\\Eloquent\\Fields\\DateTime' ];
    }

    /*
     * DateTime
     */

    protected function resolveDateTimeAttributeField(Attribute $attribute): string
    {
        $name = Str::camel($attribute->column);
        return "DateTime::make('$name')->sortable(),";
    }

    protected function resolveDateTimeAttributeFieldDependencies(Attribute $attribute): array
    {
        return [ 'LaravelJsonApi\\Eloquent\\Fields\\DateTime' ];
    }

    /*
     * ArrayList
     */

    protected function resolveArrayListAttributeField(Attribute $attribute): string
    {
        $name = Str::camel($attribute->column);
        return "ArrayList::make('$name')->sortable(),";
    }

    protected function resolveArrayListAttributeFieldDependencies(Attribute $attribute): array
    {
        return [ 'LaravelJsonApi\\Eloquent\\Fields\\ArrayList' ];
    }

    /*
     * ArrayHash
     */

    protected function resolveArrayHashAttributeField(Attribute $attribute): string
    {
        $name = Str::camel($attribute->column);
        return "ArrayHash::make('$name')->sortable(),";
    }

    protected function resolveArrayHashAttributeFieldDependencies(Attribute $attribute): array
    {
        return [ 'LaravelJsonApi\\Eloquent\\Fields\\ArrayHash' ];
    }
}
