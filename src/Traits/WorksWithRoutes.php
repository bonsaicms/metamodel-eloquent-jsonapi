<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Stub;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\Support\PhpDependenciesCollection;
use BonsaiCms\Support\Stubs\Actions\SkipEmptyLines;
use BonsaiCms\Support\Stubs\Actions\TrimNewLinesFromTheEnd;
use BonsaiCms\MetamodelEloquentJsonApi\Exceptions\RoutesAlreadyExistsException;

trait WorksWithRoutes
{
    public function deleteRoutes(bool $force = false): self
    {
        if ($this->routesExist()) {
            File::delete(
                $this->getRoutesFilePath()
            );

            if (!$force && Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.routes.canBeEmpty')) {
                $this->generateEmptyRoutes();
            }
        }

        return $this;
    }

    public function regenerateRoutes(Collection $forEntities = null, bool $force = false): self
    {
        $this->deleteRoutes(true);
        $this->generateRoutes($forEntities, $force);

        return $this;
    }

    /**
     * @throws RoutesAlreadyExistsException
     */
    public function generateRoutes(Collection $forEntities = null, bool $force = false): self
    {
        if ($this->routesExist()) {
            throw new RoutesAlreadyExistsException();
        }

        if ($this->shouldGenerateRoutes() || $force) {
            File::ensureDirectoryExists(
                $this->getRoutesDirectoryPath()
            );

            File::put(
                path: $this->getRoutesFilePath(),
                contents: $this->getRoutesContents($forEntities)
            );
        }

        return $this;
    }

    /**
     * @throws RoutesAlreadyExistsException
     */
    public function generateEmptyRoutes(): self
    {
        return $this->generateRoutes(collect([]));
    }

    public function shouldGenerateRoutes(): bool
    {
        return
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.routes.canBeEmpty') ||
            (Entity::count() > 0);
    }

    public function routesExist(): bool
    {
        return File::exists(
            $this->getRoutesFilePath()
        );
    }

    public function getRoutesDirectoryPath(): string
    {
        return
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.routes.folder').
            '/';
    }

    public function getRoutesFilePath(): string
    {
        return $this->getRoutesDirectoryPath().
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.routes.file');
    }

    public function getRoutesContents(Collection $forEntities = null): string
    {
        return Stub::make('routes/file', [
            'dependencies' => $this->resolveRoutesDependencies($forEntities),
            'comment' => $this->resolveRoutesComment($forEntities),
            'server' => $this->resolveRoutesServer($forEntities),
        ]);
    }

    protected function resolveRoutesDependencies(Collection $forEntities = null): string
    {
        $dependencies = new PhpDependenciesCollection(
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.routes.dependencies')
        );

        if (Entity::query()
            // TODO: DRY
            ->when($forEntities !== null, function ($query) use ($forEntities) {
                $query->whereIn((new Entity)->getKeyName(), $forEntities->map->getKey());
            })
            ->has('leftRelationships')
            ->orHas('rightRelationships')
            ->exists()) {
            $dependencies->push(\LaravelJsonApi\Laravel\Routing\Relationships::class);
        }

        return $dependencies->toPhpUsesString(
            $this->resolveRoutesNamespace()
        );
    }

    protected function resolveRoutesNamespace(): string
    {
        return Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.routes.namespace');
    }

    protected function resolveRoutesComment(Collection $forEntities = null): string
    {
        return Stub::make('routes/comment');
    }

    protected function resolveRoutesServer(Collection $forEntities = null): string
    {
        return app(Pipeline::class)
            ->send(
                Stub::make('routes/server', [
                    'routes' => $this->resolveRoutesDefinitions($forEntities),
                ])
            )
            ->through([
                TrimNewLinesFromTheEnd::class,
            ])
            ->thenReturn();
    }

    protected function resolveRoutesDefinitions(Collection $forEntities = null): string
    {
        return app(Pipeline::class)
            ->send(
                Entity::query()
                    // TODO: DRY
                    ->when($forEntities !== null, function ($query) use ($forEntities) {
                        $query->whereIn((new Entity)->getKeyName(), $forEntities->map->getKey());
                    })
                    ->with([
                        'attributes',
                        'leftRelationships',
                        'rightRelationships',
                    ])
                    ->orderBy('id') // TODO
                    ->get()
                    ->map(
                        fn ($entity) => $this->resolveRoutesDefinitionsForEntity($entity)
                    )
                    ->join(PHP_EOL)
            )
            ->through([
                SkipEmptyLines::class,
                TrimNewLinesFromTheEnd::class,
            ])
            ->thenReturn();
    }

    protected function resolveRoutesDefinitionsForEntity(Entity $entity): string
    {
        return Stub::make('routes/resource', [
            'resource' => Str::of($entity->name)->plural()->kebab(),
            'relationships' => $this->resolveRoutesRelationshipsForEntity($entity),
            'actions' => $this->resolveRoutesActionsForEntity($entity),
        ]);
    }

    protected function resolveRoutesRelationshipsForEntity(Entity $entity): string
    {
        $relationships = $this->getRoutesSortedRelationshipsForEntity($entity);

        if ($relationships->isEmpty()) {
            return '';
        }

        return Stub::make('routes/relationships', [
            'relationships' => app(Pipeline::class)
                ->send($relationships
                    ->map(
                        fn ($relationship) => $this->resolveRoutesRelationshipsForEntityRelationship($entity, $relationship)
                    )
                    ->join(PHP_EOL)
                )
                ->through([
                    SkipEmptyLines::class,
                    TrimNewLinesFromTheEnd::class,
                ])
                ->thenReturn()
        ]);
    }

    protected function resolveRoutesRelationshipsForEntityRelationship(Entity $entity, Relationship $relationship): string
    {
        if ($relationship->cardinality === 'oneToOne') {
            $relationshipCardinality = 'One';
        } else if ($relationship->cardinality === 'manyToMany') {
            $relationshipCardinality = 'Many';
        } else {
            $relationshipCardinality = ($entity->is($relationship->leftEntity))
                ? 'Many'
                : 'One';
        }

        return Stub::make("routes/has{$relationshipCardinality}Relationship", [
            'relationshipName' => ($entity->is($relationship->leftEntity))
                ? $relationship->left_relationship_name
                : $relationship->right_relationship_name,
        ]);
    }

    // TODO: DRY
    protected function getRoutesSortedRelationshipsForEntity(Entity $entity): Collection
    {
        return $this->sortRoutesRelationships(
            $entity
                ->leftRelationships
                ->merge(
                    $entity->rightRelationships
                )
        );
    }

    // TODO: more custom method name
    protected function sortRoutesRelationships(Collection $relationships): Collection
    {
        // TODO: implement some sort
        // TODO: DRY

        return $relationships;
    }

    protected function resolveRoutesActionsForEntity(Entity $entity): string
    {
        return Stub::make('routes/actions', [
            //
        ]);
    }
}
