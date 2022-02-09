<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Exceptions;

use Throwable;
use BonsaiCms\Metamodel\Models\Entity;

class RequestAlreadyExistsException extends AbstractException
{
    public function __construct(public Entity $entity, $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
