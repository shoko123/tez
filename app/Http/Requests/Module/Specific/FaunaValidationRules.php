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
        return ['taxa', 'bone'];
    }

    public function allowed_tagger_field_names(): array
    {
        return ['primary_taxon_id', 'scope_id', 'material_id', 'symmetry', 'weathering'];
    }

    public function commonRules()
    {
        return [
            'data.fields.locus_id' => 'required|exists:loci,id',
            'data.fields.code' => 'required|in:FL,AR,LB',
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
            'data.fields.notes' => 'max:200',
            'data.fields.has_butchery_evidence' => 'numeric|nullable|between:0,1',
            'data.fields.has_burning_evidence' => 'numeric|nullable|between:0,1',
            'data.fields.has_other_bsm_evidence' => 'numeric|nullable|between:0,1',
            'data.fields.is_fused' => 'numeric|nullable|between:0,1',
            'data.fields.is_left' => 'required|numeric|between:0,1',
            'data.fields.fauna_element_id' => 'required|exists:fauna_elements,id',
            'data.fields.primary_taxon_id' => 'required|exists:fauna_primary_taxa,id',
            // TODO - convert decimal -> uint, rules
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
            ->merge(['data.fields.id' => 'required|exists:fauna,id',])
            ->toArray();
    }
}
