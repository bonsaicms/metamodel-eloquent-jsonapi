<?php

namespace App\JsonApi\TestApi\BlueDogs;

use TestApp\Models\BlueDog;
use Testing\My\Custom\AbstractSchema;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Contracts\Schema\Sortable;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Filters\WhereIdNotIn;
use LaravelJsonApi\Eloquent\Sorting\SortCountable;
use LaravelJsonApi\Eloquent\Fields\Relations\HasMany;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;

class BlueDogCustomSchemaClassSuffix extends AbstractSchema
{
    /**
     * The model the schema corresponds to.
     *
     * @var string
     */
    public static string $model = BlueDog::class;

    /**
     * Get the resource fields.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            ID::make(),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),
            HasMany::make('redCats')->type('red-cats')->canCount(),
        ];
    }

    /**
     * Get additional sortables.
     *
     * Get sortables that are not the resource ID or a resource attribute.
     *
     * @return Sortable[]|iterable
     */
    public function sortables(): iterable
    {
        return [
            SortCountable::make($this, 'redCats'),
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
