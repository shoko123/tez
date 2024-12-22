<?php

namespace App\Http\Requests\Module\Specific;

class LocusValidationRules extends ValidationRules
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
        return ['area_id', 'season_id'];
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
