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
        return [
            'data.fields.id' => 'max:250',
        ];
    }

    public function update_rules(): array
    {
        return [
            'data.fields.id' => 'required|max:50',
        ];
    }
}
