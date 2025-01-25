<?php

namespace App\Http\Requests\Module\Specific;

class LithicValidationRules extends ValidationRules
{
    public function allowed_categorized_filter_group_names(): array
    {
        return ['Registration Scope'];
    }

    public function allowed_search_field_names(): array
    {
        return ['periods', 'description'];
    }

    public function allowed_tagger_field_names(): array
    {
        return ['lithic_primary_classification_id'];
    }

    public function commonRules()
    {
        return [
            'data.fields.locus_id' => 'required|exists:loci,id',
            'data.fields.code' => 'required|in:AR,FL',
            'data.fields.basket_no' => 'required|numeric|between:0,99',
            'data.fields.artifact_no' => 'required|numeric|between:0,99',
            'data.fields.date_retrieved' => 'date|nullable',
            'data.fields.field_description' => 'max:100',
            'data.fields.registration_notes' => 'max:100',
            'data.fields.specialist_notes' => 'max:100',
            'data.onps' => 'array',
            'data.onps.*.id' => 'required|exists:lithic_onps,id',
            'data.onps.*.value' => 'required|numeric|between:1,999',
        ];
    }

    public function create_rules(): array
    {
        return collect($this->commonRules())
            ->merge(['data.fields.id' => 'required|unique:lithics,id'])
            ->toArray();
    }

    public function update_rules(): array
    {
        return  collect($this->commonRules())
            ->merge(['data.fields.id' => 'required|exists:lithics,id',])
            ->toArray();
    }
}
