<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Stub;
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
        $stub = new Stub('routes/file');

        return $stub->generate();
    }
}
