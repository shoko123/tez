<?php

namespace App\Http\Requests\Module\Specific;

class LithicValidationRules extends ValidationRules
{
    public function allowed_categorized_filter_group_names(): array
    {
        return ['Scope'];
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
            'fields.locus_id' => 'required|exists:loci,id',
            'fields.code' => 'required|in:FL,AR,LB',
            'fields.basket_no' => 'required|numeric|between:0,99',
            'fields.artifact_no' => 'required|numeric|between:0,99',
            'fields.date_retrieved' => 'date|nullable',
            'fields.field_description' => 'max:400',
            'fields.field_notes' => 'max:400',
            'fields.artifact_count' => 'max:10',
            'fields.square' => 'max:20',
            'fields.level_top' => 'max:20',
            'fields.level_bottom' => 'max:20',
            //
            'fields.description' => 'max:400',
            'fields.width' => 'numeric|nullable|min:1|max:500',
            'fields.length' => 'numeric|nullable|min:1|max:500',
            'fields.thickness' => 'numeric|nullable|min:1|max:500',
            'fields.weight' => 'numeric|nullable|min:1|max:500',
            'fields.burnt' => 'required|numeric|between:0,1',
            'fields.rolled' => 'required|numeric|between:0,1',
            'fields.hinge' => 'required|numeric|between:0,1',
            'fields.lithic_primary_classification_id' => 'required|exists:lithic_primary_classifications,id',
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
            ->merge(['fields.id' => 'required|exists:ceramics,id',])
            ->toArray();
    }
}
