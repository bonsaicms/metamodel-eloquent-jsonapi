<?php

namespace App\JsonApi\TestApi\BlueDogs;

use Testing\My\Custom\AbstractRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class BlueDogCustomRequestClassSuffix extends AbstractRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // Attributes
            //

            // Relationships
            'redCat' => [
                JsonApiRule::toOne(),
            ],
        ];
    }
}
