<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Stub;
use BonsaiCms\Support\PhpDependenciesCollection;
use BonsaiCms\Support\Stubs\Actions\SkipEmptyLines;
use BonsaiCms\Support\Stubs\Actions\TrimNewLinesFromTheEnd;
use BonsaiCms\MetamodelEloquentJsonApi\Exceptions\RoutesAlreadyExistsException;

trait WorksWithRoutes
{
    public function deleteRoutes(): self
    {
        if ($this->routesExist()) {
            File::delete(
                $this->getRoutesFilePath()
            );
        }

        return $this;
    }

    public function regenerateRoutes(): self
    {
        $this->deleteRoutes();
        $this->generateRoutes();

        return $this;
    }

    /**
     * @throws RoutesAlreadyExistsException
     */
    public function generateRoutes(): self
    {
        if ($this->routesExist()) {
            throw new RoutesAlreadyExistsException();
        }

        if ($this->shouldGenerateRoutes()) {
            File::ensureDirectoryExists(
                $this->getRoutesDirectoryPath()
            );

            File::put(
                path: $this->getRoutesFilePath(),
                contents: $this->getRoutesContents()
            );
        }

        return $this;
    }

    public function shouldGenerateRoutes(): bool
    {
        return Entity::count() > 0;
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

    public function getRoutesContents(): string
    {
        return Stub::make('routes/file', [
            'dependencies' => $this->resolveRoutesDependencies(),
            'comment' => $this->resolveRoutesComment(),
            'server' => $this->resolveRoutesServer(),
        ]);
    }

    protected function resolveRoutesDependencies(): string
    {
        $dependencies = new PhpDependenciesCollection(
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.routes.dependencies')
        );

        // TODO: other dependencies

        return $dependencies->toPhpUsesString(
            $this->resolveRoutesNamespace()
        );
    }

    protected function resolveRoutesNamespace(): string
    {
        return Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.routes.namespace');
    }

    protected function resolveRoutesComment(): string
    {
        return Stub::make('routes/comment');
    }

    protected function resolveRoutesServer(): string
    {
        return app(Pipeline::class)
            ->send(
                Stub::make('routes/server', [
                    'routes' => $this->resolveRoutesDefinitions(),
                ])
            )
            ->through([
                TrimNewLinesFromTheEnd::class,
            ])
            ->thenReturn();
    }

    protected function resolveRoutesDefinitions(): string
    {
        return app(Pipeline::class)
            ->send(
                Entity::query()
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
        $resource = Str::of($entity->name)->plural()->kebab();

        return '$server->resource(\''.$resource.'\', \'\\\\\' . JsonApiController::class);'.PHP_EOL;
    }
}
