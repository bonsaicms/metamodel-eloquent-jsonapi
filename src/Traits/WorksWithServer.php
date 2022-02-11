<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Traits;

use Illuminate\Support\Str;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquentJsonApi\Stub;
use BonsaiCms\Support\Stubs\Actions\SkipEmptyLines;
use BonsaiCms\Support\Stubs\Actions\TrimNewLinesFromTheEnd;
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

        $stub->schemas = $this->resolveServerSchemas();

        return $stub->generate();
    }

    protected function resolveServerSchemas(): string
    {
        $entities = Entity::query()
            ->with([
                'attributes',
                'leftRelationships',
                'rightRelationships',
            ])
            ->orderBy('id') // TODO
            ->get();

        return ($entities->isEmpty())
            ? '//'
            : app(Pipeline::class)
                ->send(
                    $entities
                        ->map(
                            fn ($entity) => $this->resolveServerSchemaForEntity($entity)
                        )
                        ->join(PHP_EOL)
                )
                ->through([
                    SkipEmptyLines::class,
                    TrimNewLinesFromTheEnd::class,
                ])
                ->thenReturn();
    }

    protected function resolveServerSchemaForEntity(Entity $entity): string
    {
        return Str::plural($entity->name).'\\'.$entity->name.'Schema::class,';
    }
}
