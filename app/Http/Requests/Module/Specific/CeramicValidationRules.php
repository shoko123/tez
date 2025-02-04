<?php

namespace App\Http\Requests\Module\Specific;

class CeramicValidationRules extends ValidationRules
{
    public function allowed_categorized_filter_group_names(): array
    {
        return ['Registration Scope', 'Specialist', 'Includes Date'];
    }

    public function allowed_search_field_names(): array
    {
        return ['periods', 'specialist_description'];
    }

    public function allowed_tagger_field_names(): array
    {
        return ['primary_classification_id'];
    }

    public function commonRules()
    {
        return [
            'data.fields.locus_id' => 'required|exists:loci,id',
            'data.fields.code' => 'required|in:PT,AR',
            'data.fields.basket_no' => 'required|numeric|between:0,99',
            'data.fields.artifact_no' => 'required|numeric|between:0,99',
            'data.fields.date_retrieved' => 'date|nullable',
            'data.fields.field_description' => 'max:400',
            'data.fields.field_notes' => 'max:400',
            'data.fields.square' => 'max:20',
            'data.fields.level_top' => 'max:20',
            'data.fields.level_bottom' => 'max:20',
            //
            'data.fields.specialist' => 'required|in:Unassigned,Tamar Shooval and Danny Rosenberg,Eliot Braun,Estelle Orrelle',
            'data.fields.specialist_description' => 'max:400',
            'data.fields.periods' => 'max:250',
            'data.fields.primary_classification_id' => 'required|exists:ceramic_primary_classifications,id',
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
