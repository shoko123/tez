<?php

namespace App\Http\Requests\Module;

use App\Exceptions\GeneralJsonException;
use App\Http\Requests\Module\Specific\ValidationRules;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Module\DigModuleModel;
use App\Services\App\Utils\GetService;

class ModuleRequest extends FormRequest
{
    protected ValidationRules $rules;
    protected DigModuleModel $model;

    protected function prepareForValidation(): void
    {
        //Verify that the module is valid as it used as a key for other validations using $moduleTable[] above.
        if (is_null($this->input('module'))) {
            throw new GeneralJsonException('No module name supplied!', 422);
        }

        $module = $this->input('module');

        if (! in_array($module, [
            'Area',
            'Season',
            'Survey',
            'Locus',
            'Ceramic',
            'Fauna',
            'Glass',
            'Lithic',
            'Metal',
            'Stone',
        ])) {
            throw new GeneralJsonException('Invalid module name: `' . $this->input('module') . '`', 422);
        }

        $validation_full_class = 'App\Http\Requests\Module\Specific\\' . $module . 'ValidationRules';
        $this->rules = new $validation_full_class;
        $this->model = GetService::getModel($module, true);
    }

    protected function rule_module_name_is_valid()
    {
        return 'required|in:Area,Season,Survey,Locus,Ceramic,Fauna,Glass,Lithic,Metal,Stone';
    }

    // Generic for all modules
    //////////////////////////
    protected function rule_id_exists_in_module_table(): string
    {
        return 'exists:' . $this->model->tableName() . ',id';
    }

    protected function rule_id_exists_in_module_tags_table(): string
    {
        return 'exists:' . $this->model->tagTableName() . ',id';
    }

    protected function rule_id_exists_in_onps_table(): string
    {
        return 'exists:' . $this->model->onpTableName() . ',id';
    }

    protected function rule_discrete_value_filter_group_name_is_valid(): string
    {
        $groupNames = array_keys($this->model->discreteFilterOptions());
        return 'in:' . implode(',', $groupNames);
    }

    protected function rule_order_by_group_name_is_valid(): string
    {
        $groupNames = array_keys($this->model->orderByOptions());
        return 'in:' . implode(',', $groupNames);
    }

    // Module specific
    //////////////////
    protected function rule_categorized_group_name_is_valid(): string
    {
        return $this->rules->rule_categorized_group_name_is_valid();
    }

    protected function rule_search_field_name_is_valid(): string
    {
        return $this->rules->rule_search_field_name_is_valid();
    }

    protected function rule_tagger_field_name_is_valid(): string
    {
        return $this->rules->rule_tagger_field_name_is_valid();
    }

    protected function create_rules(): array
    {
        return $this->rules->create_rules();
    }

    protected function update_rules(): array
    {
        return $this->rules->update_rules();
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('Authorization failed in ModuleRequest.');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return ['module' => $this->rule_module_name_is_valid()];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ], 400));
    }
}
