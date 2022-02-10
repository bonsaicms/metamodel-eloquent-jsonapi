<?php

namespace App\JsonApi\TestApi\RedCats;

use Testing\My\Custom\AbstractRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class RedCatCustomRequestClassSuffix extends AbstractRequest
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
            'blueDog' => [
                JsonApiRule::toOne(),
            ],
        ];
    }
}
