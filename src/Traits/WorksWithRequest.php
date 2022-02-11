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
    use WorksWithRequestAttributesRules;
    use WorksWithRequestRelationshipsRules;

    public function deleteRequest(Entity $entity): self
    {
        if ($this->requestExists($entity)) {
            File::delete(
                $this->getRequestFilePath($entity)
            );
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
        return File::exists(
            $this->getRequestFilePath($entity)
        );
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
        $stub = new Stub('request/class');

        // Global variables
        $stub->namespace = $this->resolveRequestNamespace($entity);
        $stub->parentModel = class_basename(Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.request.parentModel'));
        $stub->className = $this->resolveRequestClassName($entity);

        // Dependencies
        $stub->dependencies = $this->resolveRequestDependencies($entity);

        // Properties
        $stub->properties = $this->resolveRequestProperties($entity);

        // Methods
        $stub->methods = $this->resolveRequestMethods($entity);

        return $stub->generate();
    }

    protected function resolveRequestNamespace(Entity $entity): string
    {
        // TODO: co ak je namespace prazdny string? - lepsie nakodit
        return Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.request.namespace')
            .'\\'
            .Str::plural($entity->name);
    }

    protected function resolveRequestClassName(Entity $entity): string
    {
        return $entity->name
            .Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.request.classSuffix');
    }

    protected function resolveRequestDependencies(Entity $entity): string
    {
        $dependencies = new PhpDependenciesCollection;

        $dependencies->push(
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.request.parentModel')
        );

        $dependencies = $dependencies->merge(
            $this->resolveRequestAttributeRulesDependencies($entity)
        );

        $dependencies = $dependencies->merge(
            $this->resolveRequestRelationshipRulesDependencies($entity)
        );

        // TODO: other dependencies

        return $dependencies->toPhpUsesString(
            $this->resolveRequestNamespace($entity)
        );
    }

    protected function resolveRequestProperties(Entity $entity): string
    {
        return Stub::make('request/properties');
    }

    protected function resolveRequestMethods(Entity $entity): string
    {
        return Stub::make('request/methods', [
            'rulesMethod' => $this->resolveRulesMethod($entity),
        ]);
    }

    protected function resolveRulesMethod(Entity $entity): string
    {
        return Stub::make(
            'request/rulesMethod',
            [
                'attributesRules' => $this->resolveRequestAttributesRules($entity),
                'relationshipsRules' => $this->resolveRequestRelationshipsRules($entity),
            ]
        );
    }
}
