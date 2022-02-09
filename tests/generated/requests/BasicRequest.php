<?php

namespace App\JsonApi\TestApi\Articles;

use Testing\My\Custom\AbstractRequest;

class ArticleCustomRequestClassSuffix extends AbstractRequest
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
            //
        ];
    }
}
