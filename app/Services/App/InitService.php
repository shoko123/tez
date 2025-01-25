<?php

namespace App\Services\App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Services\App\Utils\GetService;
use App\Services\App\DigModuleService;
use App\Services\App\Module\InitDetailsInterface;
use App\Models\Tag\TagGroup;
use App\Exceptions\GeneralJsonException;
use App\Services\App\InitGlobalGroups;

class InitService extends DigModuleService
{
    protected Model $moduleTagGroup;
    protected InitDetailsInterface $details;

    public function __construct(string $module)
    {
        parent::__construct($module);
        if ($this->uses_tags()) {
            $this->moduleTagGroup = GetService::getTagGroupModel($module);
        }
        $this->details = GetService::getDetails('Init', $module);
    }

    public function init(): array
    {
        return [
            'module' => self::$module,
            'welcome_text' => $this->details::welcomeText(),
            'counts' => [
                'items' => $this->model->count(),
                'media' => Media::where('model_type', class_basename($this->model))->count(),
            ],
            'display_options' => $this->details::displayOptions(),
            'first_id' => $this->model->select('id')->firstOrFail()['id'],
            'dateFields' => $this->model->dateFields(),
            'trio' => $this->trio(),
        ];
    }

    protected function allGroups(): array
    {
        return array_merge(InitGlobalGroups::groups(), $this->details::modelGroups());
    }

    public function trio(): array
    {
        $trio = [];

        foreach ($this->details::categories() as $name => $labels) {
            $category = ['name' => $name, 'groups' => []];
            foreach ($labels as $label) {
                array_push($category['groups'], $this->getGroupDetails($label));
            }
            array_push($trio, $category);
        }

        return $trio;
    }

    public function getGroupDetails($label): array
    {
        $group = $this->allGroups()[$label] ?? null;
        throw_if(is_null($group), new GeneralJsonException('***MODEL INIT() ERROR*** getGroupDetails() invalid label: ' . $label, 500));

        switch ($group['code']) {
            case 'TG': //global tags
                return $this->getGlobalTagsGroupDetails($label, $group);

            case 'TM': //module tags
                return $this->getModelTagsGroupDetails($label, $group);

            case 'ON':
                return $this->getOptionalNumericPropertyGroupDetails($label, $group);

            case 'LV': // lookup values 
                return $this->getLookupFieldGroupDetails($label, $group);

            case 'RV':  // only filter options come from specific restricted values lists;
                // they often belong to a different module.
                // e.g. to filter Stone by area we use the area restricted values list
                return $this->getRVGroupDetails($label, $group);

            case 'EM':  // enum columns
                return $this->getEnumGroupDetails($label, $group);

            case 'OB': // order by values 
                return $this->getOrderByGroupDetails($label, $group);

            case 'SF': //field search
            case 'CT': //categorized group
            case 'MD': //media
                return $group;

            default:
                throw new GeneralJsonException('***MODEL INIT() ERROR*** getGroupDetails() invalid code: ' . $group['code'], 500);
        }
    }

    private function getOrderByGroupDetails($label, $group)
    {
        $options = $this->model::orderByOptions();

        foreach ($group['options'] as $item) {
            if (!array_key_exists($item, $options)) {
                throw new GeneralJsonException('***MODEL INIT() ERROR*** Order By option "' . $item . '" does not exist!', 500);
            }
        }
        return array_merge(['label' => $label], $group);
    }

    private function getRVGroupDetails($label, $group)
    {
        // throw_if(is_null(self::$restrictedValues[$label]), new GeneralJsonException('** MODEL INIT ERROR - RVGroup Bad format for "' . $label . '" ***', 500));

        $model = GetService::getModel($group['values_source_module']);
        $resVals =  $model::restrictedValues();
        $specific = $resVals[$group['values_source_field']];
        $manipulator = array_key_exists('manipulator', $specific) ? $specific['manipulator'] : function ($val) {
            return $val;
        };

        $mapFunc = function ($y) use ($manipulator) {
            return ['label' => $manipulator($y), 'id' => $y];
        };

        $options = array_map($mapFunc, $specific['vals']);

        return array_merge($group, [
            'label' => $label,
            'options' => $options,
            'dependency' => []
        ]);
    }

    private function getEnumGroupDetails($label, $group)
    {
        $vals = self::getEnumValues(self::$tableName, $group['field_name']);
        $options = array();

        foreach ($vals as $i => $value) {
            array_push($options, ['label' => $value, 'index' => $i + 1]);
        }

        return array_merge($group, [
            'label' => $label,
            'options' => $options
        ]);
    }

    protected static function getEnumValues($table, $column)
    {
        $expression = DB::raw("SHOW COLUMNS FROM $table WHERE Field = '$column'");
        $string = $expression->getValue(DB::connection()->getQueryGrammar());
        $type = DB::select($string)[0]->Type;

        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = [];
        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            array_push($enum, $v);
        }
        return $enum;
    }

    private function getLookupFieldGroupDetails($label, $group)
    {
        $options = DB::table($group['lookup_table_name'])->get();
        $text_field = $group['lookup_text_field'];

        unset($group['lookup_table_name']);
        unset($group['lookup_text_field']); // May or may not exist!

        return array_merge($group, [
            'label' => $label,
            'options' => $options->map(function ($y, $key) use ($text_field) {
                return ['label' => $y->$text_field, 'id' => $y->id];
            }),
        ]);
    }

    private function getModelTagsGroupDetails($label, $group)
    {
        $tg = $this->moduleTagGroup->with(['tags' => function ($q) {
            $q->select('id', 'name', 'tag_group_id');
        }])
            ->select('id', 'multiple')
            ->where('name', $label)
            ->first();

        throw_if(is_null($tg), new GeneralJsonException('** MODEL INIT ERROR - Group "' . $label . '" not found in module.tag_groups table ***', 500));

        return array_merge($group, [
            'label' => $label,
            'tag_group_id' => $tg->id,
            'multiple' => $tg->multiple,
            'options' => $tg->tags->map(function ($y) {
                return [
                    'label' => $y->name,
                    'tag_id' => $y->id,

                ];
            }),
        ]);
    }

    private function getGlobalTagsGroupDetails($label, $group)
    {
        $gtg = TagGroup::with(['tags' => function ($q) {
            $q->select('id', 'name', 'tag_group_id');
        }])
            ->select('id', 'name')
            ->where('name', $label)
            ->first();

        return array_merge($group, [
            'label' => $label,
            'tag_group_id' => $gtg->id,
            'multiple' => true,
            'options' => $gtg->tags->map(function ($y) {
                return [
                    'label' => $y->name,
                    'tag_id' => $y->id,
                ];
            }),
        ]);
    }

    private function getOptionalNumericPropertyGroupDetails($label, $group)
    {
        $onpName = get_class($this->model) . 'OnpGroup';
        $onpGroupModel = new $onpName;
        $onpGroup = $onpGroupModel->with(['onps' => function ($q) {
            $q->select('id', 'label', 'onp_group_id');
        }])
            ->select('id')
            ->where('label', $label)
            ->first();

        throw_if(is_null($onpGroup), new GeneralJsonException('** MODEL INIT ERROR - Group "' . $label . '" not found in module.onps table ***', 500));

        return array_merge($group, [
            'label' => $label,
            'onp_group_id' => $onpGroup->id,
            'options' => $onpGroup->onps->map(function ($y) {
                return [
                    'label' => $y->label,
                    'onp_id' => $y->id,
                ];
            }),
        ]);
    }
}
