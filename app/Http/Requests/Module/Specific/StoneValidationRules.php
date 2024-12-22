<?php

namespace App\Http\Requests\Module\Specific;

use App\Models\Module\Specific\Stone\Stone;

class StoneValidationRules extends ValidationRules
{
    public function allowed_categorized_filter_group_names(): array
    {
        return [];
    }

    public function allowed_search_field_names(): array
    {
        return ['id'];
    }

    public function allowed_tagger_field_names(): array
    {
        return ['stone_primary_classification_id', 'material_id'];
    }


    public function commonRules()
    {
        return [
            'data.fields.locus_id' => 'required|exists:loci,id',
            'data.fields.code' => 'required|in:GS,AR',
            'data.fields.basket_no' => 'required|numeric|between:0,99',
            'data.fields.artifact_no' => 'required|numeric|between:1,99',
            'data.fields.date_retrieved' => 'date|nullable',
            'data.fields.field_description' => 'max:400',
            'data.fields.field_notes' => 'max:400',
            'data.fields.artifact_count' => 'max:10',
            'data.fields.square' => 'max:20',
            'data.fields.level_top' => 'max:20',
            'data.fields.level_bottom' => 'max:20',
            //
            'data.fields.description' => 'max:400',
            'data.fields.notes' => 'max:400',
            'data.fields.weight' => 'numeric|nullable|min:1|max:500',
            'data.fields.length' => 'numeric|nullable|min:1|max:500',
            'data.fields.width' => 'numeric|nullable|min:1|max:500',
            'data.fields.depth' => 'numeric|nullable|min:1|max:500',
            'data.fields.thickness_min' => 'numeric|nullable|min:1|max:500',
            'data.fields.thickness_max' => 'numeric|nullable|min:1|max:500',
            'data.fields.perforation_diameter_max' => 'numeric|nullable|min:1|max:500',
            'data.fields.perforation_diameter_min' => 'numeric|nullable|min:1|max:500',
            'data.fields.perforation_depth' => 'numeric|nullable|min:1|max:500',
            'data.fields.diameter' => 'numeric|nullable|min:1|max:500',
            'data.fields.rim_diameter' => 'numeric|nullable|min:1|max:500',
            'data.fields.rim_thickness' => 'numeric|nullable|min:1|max:500',
            'data.fields.base_diameter' => 'numeric|nullable|min:1|max:500',
            'data.fields.base_thickness' => 'numeric|nullable|min:1|max:500',
            'data.fields.stone_primary_classification_id' => 'required|exists:stone_primary_classifications,id',
            'data.fields.material_id' => 'required|exists:stone_materials,id',
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
            ->merge(['data.fields.id' => 'required|exists:stones,id',])
            ->toArray();
    }
}
