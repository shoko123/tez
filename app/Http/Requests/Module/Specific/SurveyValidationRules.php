<?php

namespace App\Http\Requests\Module\Specific;

class SurveyValidationRules extends ValidationRules
{
    public function allowed_categorized_filter_group_names(): array
    {
        return [];
    }

    public function allowed_search_field_names(): array
    {
        return ['description'];
    }

    public function allowed_tagger_field_names(): array
    {
        return [];
    }

    public function commonRules()
    {
        return [
            'data.fields.area_id' => 'required|exists:areas,id',
            'data.fields.feature_no' => 'required|numeric|between:1,200',
            'data.fields.surveyed_date' => 'date|nullable',
            'data.fields.elevation' => 'numeric|between:0,200|nullable',
            'data.fields.next_to' => 'max:50|nullable',
            'data.fields.description' => 'max:1000|nullable',
            'data.fields.notes' => 'max:100|nullable',
        ];
    }

    public function create_rules(): array
    {
        return collect($this->commonRules())
            ->merge(['data.fields.id' => 'required|unique:survey,id'])
            ->toArray();
    }

    public function update_rules(): array
    {
        return  collect($this->commonRules())
            ->merge(['data.fields.id' => 'required|exists:survey,id',])
            ->toArray();
    }
}
