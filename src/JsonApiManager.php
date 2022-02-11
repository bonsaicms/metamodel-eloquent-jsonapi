<?php

namespace BonsaiCms\MetamodelEloquentJsonApi;

use BonsaiCms\MetamodelEloquentJsonApi\Contracts\JsonApiManagerContract;

class JsonApiManager implements JsonApiManagerContract
{
    use Traits\WorksWithSchema;
    use Traits\WorksWithRequest;
    use Traits\WorksWithRoutes;
    use Traits\WorksWithServer;
}
