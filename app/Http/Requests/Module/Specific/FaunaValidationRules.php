<?php

namespace App\Http\Requests\Module\Specific;

class FaunaValidationRules extends ValidationRules
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
        return ['fauna_element_id', 'fauna_taxon_id'];
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
            'fields.notes' => 'max:200',
            'fields.has_butchery_evidence' => 'numeric|nullable|between:0,1',
            'fields.has_burning_evidence' => 'numeric|nullable|between:0,1',
            'fields.has_other_bsm_evidence' => 'numeric|nullable|between:0,1',
            'fields.is_fused' => 'numeric|nullable|between:0,1',
            'fields.is_left' => 'required|numeric|between:0,1',
            'fields.fauna_element_id' => 'required|exists:fauna_elements,id',
            'fields.fauna_taxon_id' => 'required|exists:fauna_taxa,id',
            // TODO - convert decimal -> uint, rules
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
            ->merge(['fields.id' => 'required|exists:fauna,id',])
            ->toArray();
    }
}
