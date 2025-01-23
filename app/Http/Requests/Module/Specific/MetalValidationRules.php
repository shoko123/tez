<?php

namespace App\Http\Requests\Module\Specific;

class MetalValidationRules extends ValidationRules
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
        return ['material_id', 'metal_primary_classification_id'];
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
            'data.fields.measurements' => 'max:200',
            'data.fields.notes' => 'max:255',
            'data.fields.material_id' => 'required|exists:metal_materials,id',
            'data.fields.metal_primary_classification_id' => 'required|exists:metal_primary_classifications,id',
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
