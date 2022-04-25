<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Config;
use BonsaiCms\MetamodelEloquentJsonApi\Stub;
use BonsaiCms\Support\PhpDependenciesCollection;
use BonsaiCms\Support\Stubs\Actions\SkipEmptyLines;
use BonsaiCms\MetamodelEloquentJsonApi\Exceptions\SchemaAlreadyExistsException;

trait WorksWithSchema
{
    use WorksWithSchemaAttributeFields;
    use WorksWithSchemaRelationshipFields;
    use WorksWithSchemaRelationshipSortables;

    public function deleteSchema(Entity $entity): self
    {
        if ($this->schemaExists($entity)) {
            File::delete(
                $this->getSchemaFilePath($entity)
            );
        }

        return $this;
    }

    public function regenerateSchema(Entity $entity): self
    {
        $this->deleteSchema($entity);

        if ($entity->exists) {
            $this->generateSchema($entity);
        }

        return $this;
    }

    /**
     * @throws SchemaAlreadyExistsException
     */
    public function generateSchema(Entity $entity): self
    {
        if ($this->schemaExists($entity)) {
            throw new SchemaAlreadyExistsException($entity);
        }

        File::ensureDirectoryExists(
            $this->getSchemaDirectoryPath($entity)
        );

        File::put(
            path: $this->getSchemaFilePath($entity),
            contents: $this->getSchemaContents($entity)
        );

        return $this;
    }

    public function schemaExists(Entity $entity): bool
    {
        return File::exists(
            $this->getSchemaFilePath($entity)
        );
    }

    public function getSchemaDirectoryPath(Entity $entity): string
    {
        return Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.schema.folder')
            .'/'
            .Str::plural($entity->name)
            .'/';
    }

    public function getSchemaFilePath(Entity $entity): string
    {
        return $this->getSchemaDirectoryPath($entity)
            .$entity->name.
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.schema.fileSuffix');
    }

    public function getSchemaContents(Entity $entity): string
    {
        $stub = new Stub('schema/class');

        // Global variables
        $stub->namespace = $this->resolveSchemaNamespace($entity);
        $stub->parentClass = class_basename(Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.schema.parentClass'));
        $stub->className = $this->resolveSchemaClassName($entity);

        // Dependencies
        $stub->dependencies = $this->resolveSchemaDependencies($entity);

        // Properties
        $stub->properties = $this->resolveSchemaProperties($entity);

        // Methods
        $stub->methods = $this->resolveSchemaMethods($entity);

        return $stub->generate();
    }

    protected function resolveSchemaNamespace(Entity $entity): string
    {
        // TODO: co ak je namespace prazdny string? - lepsie nakodit
        return Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.schema.namespace')
            .'\\'
            .Str::plural($entity->name);
    }

    protected function resolveSchemaClassName(Entity $entity): string
    {
        return $entity->name
            .Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.schema.classSuffix');
    }

    protected function resolveSchemaDependencies(Entity $entity): string
    {
        $dependencies = new PhpDependenciesCollection(
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.schema.dependencies')
        );

        $dependencies->push(
            Config::get('bonsaicms-metamodel-eloquent-jsonapi.generate.schema.parentClass')
        );

        // Model
        // TODO: co ak je Config::get('bonsaicms-metamodel-eloquent.generate.namespace') prazdny string?
        $dependencies->push(
            Config::get('bonsaicms-metamodel-eloquent.generate.models.namespace')
            .'\\'
            .$entity->name
        );

        $dependencies = $dependencies->merge(
            $this->resolveSchemaAttributeFieldsDependencies($entity)
        );

        $dependencies = $dependencies->merge(
            $this->resolveSchemaRelationshipFieldsDependencies($entity)
        );

        $dependencies = $dependencies->merge(
            $this->resolveSchemaRelationshipSortablesDependencies($entity)
        );

        return $dependencies->toPhpUsesString(
            $this->resolveSchemaNamespace($entity)
        );
    }

    protected function resolveSchemaProperties(Entity $entity): string
    {
        return Stub::make('schema/properties', [
            'modelProperty' => $this->resolveSchemaModelProperty($entity),
        ]);
    }

    protected function resolveSchemaModelProperty(Entity $entity): string
    {
        return Stub::make('schema/modelProperty', [
            'eloquentModel' => $entity->name, // TODO: zamysliet sa
        ]);
    }

    protected function resolveSchemaMethods(Entity $entity): string
    {
        return Stub::make('schema/methods', [
            'fieldsMethod' => $this->resolveSchemaFieldsMethod($entity),
            'sortablesMethod' => $this->resolveSchemaSortablesMethod($entity),
            'filtersMethod' => $this->resolveSchemaFiltersMethod($entity),
            'paginationMethod' => $this->resolveSchemaPaginationMethod($entity),
        ]);
    }

    protected function resolveSchemaFieldsMethod(Entity $entity): string
    {
        return Stub::make(
            'schema/fieldsMethod',
            [
                'attributeFields' => $this->resolveSchemaAttributeFields($entity),
                'relationshipFields' =>  $this->resolveSchemaRelationshipFields($entity),
            ],
            [
                SkipEmptyLines::class,
            ],
        );
    }

    protected function resolveSchemaFiltersMethod(Entity $entity): string
    {
        return Stub::make('schema/filtersMethod');
    }

    protected function resolveSchemaPaginationMethod(Entity $entity): string
    {
        return Stub::make('schema/paginationMethod');
    }
}
