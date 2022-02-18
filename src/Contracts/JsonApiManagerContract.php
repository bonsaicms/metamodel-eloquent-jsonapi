<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Contracts;

use Illuminate\Support\Collection;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Exceptions\SchemaAlreadyExistsException;
use BonsaiCms\MetamodelEloquentJsonApi\Exceptions\RoutesAlreadyExistsException;

interface JsonApiManagerContract
{
    /*
     * Schema
     */

    function deleteSchema(Entity $entity): self;

    function regenerateSchema(Entity $entity): self;

    /**
     * @throws SchemaAlreadyExistsException
     */
    function generateSchema(Entity $entity): self;

    function schemaExists(Entity $entity): bool;

    function getSchemaDirectoryPath(Entity $entity): string;

    function getSchemaFilePath(Entity $entity): string;

    function getSchemaContents(Entity $entity): string;

    /*
     * Request
     */

    function deleteRequest(Entity $entity): self;

    function regenerateRequest(Entity $entity): self;

    function generateRequest(Entity $entity): self;

    function requestExists(Entity $entity): bool;

    function getRequestDirectoryPath(Entity $entity): string;

    function getRequestFilePath(Entity $entity): string;

    function getRequestContents(Entity $entity): string;

    /*
     * Routes
     */

    // TODO: testy pre force
    function deleteRoutes(bool $force = false): self;

    // TODO: testy pre $forEntities
    // TODO: testy pre force
    function regenerateRoutes(Collection $forEntities = null, bool $force = false): self;

    // TODO: testy pre $forEntities
    // TODO: testy pre force
    function generateRoutes(Collection $forEntities = null, bool $force = false): self;

    /**
     * @throws RoutesAlreadyExistsException
     */
    // TODO: testy
    public function generateEmptyRoutes(): self;

    function routesExist(): bool;

    // TODO: spravit taku metodu?
//    function areRoutesEmpty(): bool;

    function getRoutesDirectoryPath(): string;

    function getRoutesFilePath(): string;

    function getRoutesContents(): string;

    /*
     * Server
     */

    function deleteServer(): self;

    function regenerateServer(): self;

    function generateServer(): self;

    function serverExists(): bool;

    function getServerDirectoryPath(): string;

    function getServerFilePath(): string;

    function getServerContents(): string;
}
