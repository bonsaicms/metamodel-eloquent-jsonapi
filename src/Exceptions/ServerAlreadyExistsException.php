<?php

namespace BonsaiCms\MetamodelEloquentJsonApi\Exceptions;

use Throwable;

class ServerAlreadyExistsException extends AbstractException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
