<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use BonsaiCms\MetamodelEloquentJsonApi\Stub;
use BonsaiCms\MetamodelEloquentJsonApi\Exceptions\ServerAlreadyExistsException;

trait WorksWithServer
{
    public function deleteServer(): self
    {
        if ($this->serverExists()) {
            File::delete(
                $this->getServerFilePath()
            );
        }

        return $this;
    }

    public function regenerateServer(): self
    {
        $this->deleteServer();
        $this->generateServer();

        return $this;
    }

    /**
     * @throws ServerAlreadyExistsException
     */
    public function generateServer(): self
    {
        if ($this->serverExists()) {
            throw new ServerAlreadyExistsException();
        }

        File::ensureDirectoryExists(
            $this->getServerDirectoryPath()
        );

        File::put(
            path: $this->getServerFilePath(),
            contents: $this->getServerContents()
        );

        return $this;
    }

    public function serverExists(): bool
    {
        return File::exists(
            $this->getServerFilePath()
        );
    }

    public function getServerDirectoryPath(): string
    {
        return Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.server.folder').'/';
    }

    public function getServerFilePath(): string
    {
        return $this->getServerDirectoryPath().
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.server.file');
    }

    public function getServerContents(): string
    {
        $stub = new Stub('server/file');

//        // Global variables
//        $stub->namespace = $this->resolveSchemaNamespace($entity);
//        $stub->parentModel = class_basename(Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.schema.parentModel'));
//        $stub->className = $this->resolveSchemaClassName($entity);
//
//        // Dependencies
//        $stub->dependencies = $this->resolveSchemaDependencies($entity);
//
//        // Properties
//        $stub->properties = $this->resolveSchemaProperties($entity);
//
//        // Methods
//        $stub->methods = $this->resolveSchemaMethods($entity);

        return $stub->generate();
    }
}
