<?php

namespace App\Http\Requests\Module\Specific;

abstract class ValidationRules
{
    abstract public function create_rules(): array;

    abstract public function update_rules(): array;

    abstract public function allowed_categorized_filter_group_names(): array;

    abstract public function allowed_search_field_names(): array;

    abstract public function allowed_tagger_field_names(): array;

    public function rule_categorized_group_name_is_valid(): string
    {
        return 'in:' . implode(',', $this->allowed_categorized_filter_group_names());
    }

    public function rule_search_field_name_is_valid(): string
    {
        return 'in:' . implode(',', $this->allowed_search_field_names());
    }

    public function rule_tagger_field_name_is_valid(): string
    {
        return 'in:' . implode(',', $this->allowed_tagger_field_names());
    }
}
