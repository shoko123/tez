<?php

namespace App\Http\Requests\Module;

use App\Rules\RuleStringIntOrBool;

class IndexRequest extends ModuleRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //'module' => verified also in ModuleRequest.prepareForValidation(),
            'module' => $this->rule_module_name_is_valid(),
            //
            'query.module_tag_ids' => ['array'],
            'query.module_tag_ids.*' => $this->rule_id_exists_in_module_tags_table(),
            //
            'query.global_tag_ids' => ['array'],
            'query.global_tag_ids.*' => 'exists:tags,id',
            //
            'query.onp_ids' => ['array'],
            'query.onp_ids.*' => $this->rule_id_exists_in_onps_table(),
            //
            //TODO validate that vals exist in the other tables' values (awkward)
            'query.discrete_field_values' => ['array'],
            'query.discrete_field_values.*.group_name' => ['required', $this->rule_discrete_value_filter_group_name_is_valid()],
            'query.discrete_field_values.*.vals' => ['array'],
            'query.discrete_field_values.*.vals.*' => ['required', new RuleStringIntOrBool()],
            //
            'query.categorized' => ['array'],
            'query.categorized.*.group_name' => ['required', $this->rule_categorized_group_name_is_valid()],
            'query.categorized.*.selected' => ['array'],
            'query.categorized.*.selected.*.name' => ['required', 'string'],
            'query.categorized.*.selected.*.index' => ['required', 'numeric', 'between:0,20'],
            //
            'query.field_search' => ['array'],
            'query.field_search.*.field_name' => [$this->rule_search_field_name_is_valid()],
            'query.field_search.*.vals' => ['array'],
            'query.field_search.*.vals.*' => ['string'],
            //
            'query.media' => ['array'],
            'query.media.*' => ['string'],
            //
            'query.order_by.*' => ['array'],
            'query.order_by.*.group_name' => ['required', $this->rule_order_by_group_name_is_valid()],
            'query.order_by.*.asc' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
