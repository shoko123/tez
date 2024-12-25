<?php

namespace App\Http\Requests\Module\Specific;

class AreaValidationRules extends ValidationRules
{
    public function allowed_categorized_filter_group_names(): array
    {
        return [];
    }

    public function allowed_search_field_names(): array
    {
        return [];
    }

    public function allowed_tagger_field_names(): array
    {
        return [];
    }

    public function create_rules(): array
    {
        return [];
    }

    public function update_rules(): array
    {
        return [
            'data.fields.id' => 'required|max:1',
            'data.fields.description' => 'max:2000',
            'data.fields.notes' => 'max:2000',
        ];
    }
}
