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
        return ['description', 'deposit', 'registration_notes'];
    }

    public function allowed_tagger_field_names(): array
    {
        return ['area_id', 'season_id'];
    }

    public function commonRules()
    {
        return [
            'data.fields.season_id' => 'required|exists:seasons,id',
            'data.fields.area_id' => 'required|exists:areas,id',
            'data.fields.locus_no' => 'required|numeric|min:1|max:999',
            'data.fields.square' => 'max:20',
            'data.fields.date_opened' => 'date|nullable',
            'data.fields.date_closed' => 'date|nullable',
            'data.fields.level_opened' => 'max:20',
            'data.fields.level_closed' => 'max:20',
            'data.fields.locus_above' => 'max:50',
            'data.fields.locus_below' => 'max:50',
            'data.fields.locus_co_existing' => 'max:50',
            'data.fields.description' => 'max:500',
            'data.fields.deposit' => 'max:500',
            'data.fields.registration_notes' => 'max:500',
            'data.fields.clean' => 'max:1',
        ];
    }

    public function create_rules(): array
    {
        return collect($this->commonRules())
            ->merge(['data.fields.id' => 'required|unique:loci,id'])
            ->toArray();
    }

    public function update_rules(): array
    {
        return  collect($this->commonRules())
            ->merge(['data.fields.id' => 'required|exists:loci,id',])
            ->toArray();
    }
}
