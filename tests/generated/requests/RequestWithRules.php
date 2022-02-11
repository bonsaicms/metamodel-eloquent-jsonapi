<?php

namespace App\JsonApi\TestApi\Pages;

use Testing\My\Custom\AbstractRequest;

class PageCustomRequestClassSuffix extends AbstractRequest
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
            'someNullableStringAttribute' => [
                'nullable',
                'string',
            ],
            'someRequiredStringAttribute' => [
                'required',
                'string',
            ],
            'someNullableTextAttribute' => [
                'nullable',
                'string',
            ],
            'someRequiredTextAttribute' => [
                'required',
                'string',
            ],
            'someNullableBooleanAttribute' => [
                'nullable',
                'boolean',
            ],
            'someRequiredBooleanAttribute' => [
                'present',
                'boolean',
            ],
            'someNullableIntegerAttribute' => [
                'nullable',
                'integer',
            ],
            'someRequiredIntegerAttribute' => [
                'required',
                'integer',
            ],
            'someNullableDateAttribute' => [
                'nullable',
                'date',
            ],
            'someRequiredDateAttribute' => [
                'required',
                'date',
            ],
            'someNullableTimeAttribute' => [
                'nullable',
                'date_format:H:i',
            ],
            'someRequiredTimeAttribute' => [
                'required',
                'date_format:H:i',
            ],
            'someNullableDatetimeAttribute' => [
                'nullable',
                'date',
            ],
            'someRequiredDatetimeAttribute' => [
                'required',
                'date',
            ],

            // Relationships
            //
        ];
    }
}
