<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Config;
use BonsaiCms\MetamodelEloquentJsonApi\Stub;
use BonsaiCms\Support\PhpDependenciesCollection;
use BonsaiCms\MetamodelEloquentJsonApi\Exceptions\RequestAlreadyExistsException;

trait WorksWithRequest
{
    public function deleteRequest(Entity $entity): self
    {
        if ($this->requestExists($entity)) {
            File::delete($this->getRequestFilePath($entity));
        }

        return $this;
    }

    public function regenerateRequest(Entity $entity): self
    {
        $this->deleteRequest($entity);

        if ($entity->exists) {
            $this->generateRequest($entity);
        }

        return $this;
    }

    public function generateRequest(Entity $entity): self
    {
        if ($this->requestExists($entity)) {
            throw new RequestAlreadyExistsException($entity);
        }

        File::ensureDirectoryExists(
            $this->getRequestDirectoryPath($entity)
        );

        File::put(
            path: $this->getRequestFilePath($entity),
            contents: $this->getRequestContents($entity)
        );

        return $this;
    }

    public function requestExists(Entity $entity): bool
    {
        return File::exists($this->getRequestFilePath($entity));
    }

    public function getRequestDirectoryPath(Entity $entity): string
    {
        return Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.request.folder')
            .'/'
            .Str::plural($entity->name)
            .'/';
    }

    public function getRequestFilePath(Entity $entity): string
    {
        return $this->getRequestDirectoryPath($entity)
            .$entity->name.
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.request.fileSuffix');
    }

    public function getRequestContents(Entity $entity): string
    {
        $stub = new Stub('request');
//
//        // Global variables
//        $stub->namespace = Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.namespace');
//        $stub->parentModel = class_basename(Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.parentModel'));
//        $stub->className = $entity->name;

        // Dependencies
        $stub->dependencies = $this->resolveRequestDependencies($entity);
//
//        // Properties
//        $stub->properties = $this->resolveProperties($entity);
//
//        // Methods
//        $stub->methods = $this->resolveMethods($entity);
//
//        return $this->postProcessModelContents($stub->generate());

        return '';
    }

    protected function resolveRequestDependencies(Entity $entity): string
    {
        $dependencies = new PhpDependenciesCollection;

        $dependencies->push(
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.request.parentModel')
        );

        // TODO: other dependencies

        return $dependencies->toPhpUsesString(
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.request.namespace')
        );
    }
}
