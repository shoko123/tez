<?php

namespace App\Http\Requests\Module\Specific;

class GlassValidationRules extends ValidationRules
{
    public function allowed_categorized_filter_group_names(): array
    {
        return [];
    }

    public function allowed_search_field_names(): array
    {
        return ['periods', 'description'];
    }

    public function allowed_tagger_field_names(): array
    {
        return ['glass_primary_classification_id'];
    }

    public function commonRules()
    {
        return [
            'data.fields.locus_id' => 'required|exists:loci,id',
            'data.fields.code' => 'required|in:AR',
            'data.fields.basket_no' => 'required|numeric|between:0,99',
            'data.fields.artifact_no' => 'required|numeric|between:0,99',
            'data.fields.date_retrieved' => 'date|nullable',
            'data.fields.field_description' => 'max:400',
            'data.fields.field_notes' => 'max:400',
            'data.fields.artifact_count' => 'max:10',
            'data.fields.square' => 'max:20',
            'data.fields.level_top' => 'max:20',
            'data.fields.level_bottom' => 'max:20',
            //
            'data.fields.description' => 'max:400',
            'data.fields.rim_diameter' => 'numeric|nullable|between:1,500',
            'data.fields.base_diameter' => 'numeric|nullable|between:1,500',
            'data.fields.bangle_diameter' => 'numeric|nullable|between:1,500',
            'data.fields.bead_diameter' => 'numeric|nullable|between:1,500',
            'data.fields.pontil_diameter' => 'required|numeric|between:1,500',
            'data.fields.glass_primary_classification_id' => 'required|exists:glass_primary_classifications,id',
        ];
    }

    public function create_rules(): array
    {
        return collect($this->commonRules())
            ->merge(['data.fields.id' => 'nullable'])
            ->toArray();
    }

    public function update_rules(): array
    {
        return  collect($this->commonRules())
            ->merge(['data.fields.id' => 'required|exists:ceramics,id',])
            ->toArray();
    }
}
