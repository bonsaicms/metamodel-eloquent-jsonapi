<?php

namespace App\JsonApi\TestApi\Pages;

use TestApp\Models\Page;
use Testing\My\Custom\AbstractSchema;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Fields\Number;
use LaravelJsonApi\Eloquent\Fields\Boolean;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ArrayHash;
use LaravelJsonApi\Eloquent\Fields\ArrayList;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;

class PageCustomSchemaClassSuffix extends AbstractSchema
{
    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = Page::class;

    /**
     * Get the resource fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            ID::make(),
            Str::make('someStringAttribute')->sortable(),
            Str::make('someTextAttribute')->sortable(),
            Boolean::make('someBooleanAttribute')->sortable(),
            Number::make('someIntegerAttribute')->sortable(),
            DateTime::make('someDateAttribute')->sortable(),
            DateTime::make('someTimeAttribute')->sortable(),
            DateTime::make('someDatetimeAttribute')->sortable(),
            ArrayList::make('someArraylistAttribute')->sortable(),
            ArrayHash::make('someArrayhashAttribute')->sortable(),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),
        ];
    }

    /**
     * Get the resource filters.
     *
     * @return array
     */
    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
            WhereIdNotIn::make($this, 'except'),
        ];
    }

    /**
     * Get the resource paginator.
     *
     * @return Paginator|null
     */
    public function pagination(): ?Paginator
    {
        return PagePagination::make();
    }
}
