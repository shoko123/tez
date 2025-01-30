<?php

namespace App\Http\Requests\Module\Specific;

class FaunaValidationRules extends ValidationRules
{
    public function allowed_categorized_filter_group_names(): array
    {
        return ['Registration Scope'];
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
            'data.fields.code' => 'required|in:AR,LB',
            'data.fields.basket_no' => 'required|numeric|between:0,99',
            'data.fields.artifact_no' => 'required|numeric|between:0,99',
            'data.fields.date_retrieved' => 'date|nullable',
            'data.fields.weight' => 'numeric|between:1,2000|nullable',
            'data.fields.field_description' => 'max:255',
            //
            'data.fields.primary_taxon_id' => 'required|exists:fauna_primary_taxa,id',
            'data.fields.scope_id' => 'required|exists:fauna_scopes,id',
            'data.fields.material_id' => 'required|exists:fauna_materials,id',
            //
            'data.fields.taxa' => 'max:400',
            'data.fields.bone' => 'max:400',
            'data.fields.symmetry' => 'required|in:Unassigned,Irrelevant,Unknown,Left,Right',
            'data.fields.d_and_r' => 'max:30',
            'data.fields.age' => 'max:50',
            'data.fields.breakage' => 'max:50',
            'data.fields.butchery' => 'max:100',
            'data.fields.burning' => 'max:100',
            'data.fields.weathering' => 'required|in:Unassigned,0,1,2,3,4,5',
            'data.fields.other_bsm' => 'max:200',
            'data.fields.specialist_notes' => 'max:200',
            'data.fields.measured' => 'max:1',
            'data.onps' => 'array',
            'data.onps.*.id' => 'required|exists:fauna_onps,id',
            'data.onps.*.value' => 'required|numeric|between:1,999',
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
