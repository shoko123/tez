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
            'fields.locus_id' => 'required|exists:loci,id',
            'fields.code' => 'required|in:GS,AR',
            'fields.basket_no' => 'required|numeric|between:0,99',
            'fields.artifact_no' => 'required|numeric|between:1,99',
            'fields.date_retrieved' => 'date|nullable',
            'fields.field_description' => 'max:400',
            'fields.field_notes' => 'max:400',
            'fields.artifact_count' => 'max:10',
            'fields.square' => 'max:20',
            'fields.level_top' => 'max:20',
            'fields.level_bottom' => 'max:20',
            //
            'fields.description' => 'max:400',
            'fields.notes' => 'max:400',
            'fields.weight' => 'numeric|nullable|min:1|max:500',
            'fields.length' => 'numeric|nullable|min:1|max:500',
            'fields.width' => 'numeric|nullable|min:1|max:500',
            'fields.depth' => 'numeric|nullable|min:1|max:500',
            'fields.thickness_min' => 'numeric|nullable|min:1|max:500',
            'fields.thickness_max' => 'numeric|nullable|min:1|max:500',
            'fields.perforation_diameter_max' => 'numeric|nullable|min:1|max:500',
            'fields.perforation_diameter_min' => 'numeric|nullable|min:1|max:500',
            'fields.perforation_depth' => 'numeric|nullable|min:1|max:500',
            'fields.diameter' => 'numeric|nullable|min:1|max:500',
            'fields.rim_diameter' => 'numeric|nullable|min:1|max:500',
            'fields.rim_thickness' => 'numeric|nullable|min:1|max:500',
            'fields.base_diameter' => 'numeric|nullable|min:1|max:500',
            'fields.base_thickness' => 'numeric|nullable|min:1|max:500',
            'fields.stone_primary_classification_id' => 'required|exists:stone_primary_classifications,id',
            'fields.material_id' => 'required|exists:stone_materials,id',
        ];
    }

    public function create_rules(): array
    {
        return collect($this->commonRules())
            ->merge(['fields.id' => 'nullable'])
            ->toArray();
    }

    public function update_rules(): array
    {
        return  collect($this->commonRules())
            ->merge(['fields.id' => 'required|exists:stones,id',])
            ->toArray();
    }
}
