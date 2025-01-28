<?php

namespace App\Services\App\Read;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use App\Services\App\DigModuleService;
use App\Services\App\Utils\GetService;
use App\Services\App\Module\ReadDetailsInterface;
use App\Models\Module\DigModuleModel;
use App\Models\Tag\Tag;

class IndexService extends DigModuleService
{
    protected DigModuleModel $model;
    protected Builder $builder;

    protected ReadDetailsInterface $details;

    public function __construct(string $module)
    {
        parent::__construct($module);
        $this->details = GetService::getDetails('Read', $module);
    }

    /////////////// index ///////////////////

    public function index(?array $query): array
    {
        $this->builder = $this->model->select('id');

        if (! is_null($query)) {
            $this->applyFilters($query);
        }

        if (empty($query['order_by'])) {
            $this->applyDefaultOrderBy();
        } else {
            $this->applyOrderBy($query['order_by']);
        }

        return $this->builder
            ->get()
            ->map(function ($item, $key) {
                return $item->id;
            })
            ->toArray();
    }

    public function applyFilters($query)
    {
        if (! empty($query['module_tag_ids'])) {
            $this->applyModelTagFilters($query['module_tag_ids']);
        }

        if (! empty($query['global_tag_ids'])) {
            $this->applyGlobalTagFilters($query['global_tag_ids']);
        }

        if (! empty($query['onp_ids'])) {
            $this->applyNumericPropertiesFilters($query['onp_ids']);
        }

        if (! empty($query['discrete_field_values'])) {
            $this->applyFieldValueFilters($query['discrete_field_values']);
        }

        if (! empty($query['categorized'])) {
            $this->builder =  $this->details::applyCategorizedFilters($this->builder, $query['categorized']); // $this->categorizedFilters($query['categorized']);
        }

        if (! empty($query['field_search'])) {
            $this->applyFieldSearchFilters($query['field_search']);
        }

        if (! empty($query['media'])) {
            $this->applyMediaFilter($query['media']);
        }
    }

    public function applyFieldValueFilters(array $cols)
    {
        $discreteFilters = $this->model::discreteFilterOptions();

        foreach ($cols as $key => $data) {
            if (!is_array($discreteFilters[$data['group_name']])) {
                $this->builder->whereIn($discreteFilters[$data['group_name']], $data['vals']);
            } else {
                $field = $discreteFilters[$data['group_name']]['field'];
                $start = $discreteFilters[$data['group_name']]['start'];
                $length = $discreteFilters[$data['group_name']]['length'];

                $qry = 'SUBSTRING(' . $field . ', ' . $start . ', ' . $length . ') ' .  ' in (' .
                    rtrim(str_repeat('?,', count($data['vals'])), ',') .
                    ')';
                $this->builder->whereRaw($qry, $data['vals']);
            }
        }
    }

    public function applyModelTagFilters(array $tag_ids)
    {
        $tagModelName = get_class($this->model) . 'Tag';
        $tagModel = new $tagModelName;
        $groups = [];

        $tags = $tagModel->select('id', 'tag_group_id')->whereIn('id', $tag_ids)->get();

        foreach ($tags as $tag) {
            if (array_key_exists($tag->tag_group_id, $groups)) {
                array_push($groups[$tag->tag_group_id], $tag->id);
            } else {
                $groups[$tag->tag_group_id] = [$tag->id];
            }
        }

        foreach ($groups as $type_id => $tag_ids_for_group) {
            $this->builder->whereHas('module_tags', function (Builder $q) use ($tag_ids_for_group) {
                $q->whereIn('id', $tag_ids_for_group);
            });
        }
    }

    public function applyNumericPropertiesFilters(array $onp_ids)
    {
        $onpName = get_class($this->model) . 'Onp';
        $onpModel = new $onpName;

        $groups = [];
        $onps = $onpModel->select('id', 'group_label')->whereIn('id', $onp_ids)->get();

        foreach ($onps as $onp) {
            if (array_key_exists($onp->group_label, $groups)) {
                array_push($groups[$onp->group_label], $onp->id);
            } else {
                $groups[$onp->group_label] = [$onp->id];
            }
        }

        foreach ($groups as $type_id => $onp_ids_for_group) {
            $this->builder->whereHas('onps', function (Builder $q) use ($onp_ids_for_group) {
                $q->whereIn('id', $onp_ids_for_group);
            });
        }
    }

    public function applyGlobalTagFilters(array $tag_ids)
    {
        $tags = Tag::select('id', 'tag_group_id')->whereIn('id', $tag_ids)->get();
        $groups = [];

        foreach ($tags as $tag) {
            if (array_key_exists($tag->tag_group_id, $groups)) {
                array_push($groups[$tag->tag_group_id], $tag->id);
            } else {
                $groups[$tag->tag_group_id] = [$tag->id];
            }
        }

        foreach ($groups as $type_id => $tag_ids_for_group) {
            $this->builder->whereHas('global_tags', function (Builder $q) use ($tag_ids_for_group) {
                $q->whereIn('id', $tag_ids_for_group);
            });
        }
    }

    public function applyFieldSearchFilters(array $cols)
    {
        foreach ($cols as $key => $col) {
            $this->builder->Where(function ($query) use ($col) {
                foreach ($col['vals'] as $key1 => $term) {
                    $query->orWhere($col['field_name'], 'LIKE', '%' . $term . '%');
                }
            });
        }
    }

    public function applyMediaFilter(array $collectionNames)
    {
        $this->builder->whereHas('media', function (Builder $mediaQuery) use ($collectionNames) {
            $mediaQuery->whereIn('collection_name', $collectionNames);
        });
    }

    public function applyOrderBy(array $order_by)
    {
        $orderBy = $this->model::orderByOptions();

        foreach ($order_by as $key => $data) {
            if (!is_array($orderBy[$data['group_name']])) {
                $this->builder->orderBy($orderBy[$data['group_name']], $data['asc'] ? 'asc' : 'desc');
            } else {
                $field = $orderBy[$data['group_name']]['field'];
                $start = $orderBy[$data['group_name']]['start'];
                $length = $orderBy[$data['group_name']]['length'];
                $direction = $data['asc'] ? 'ASC' : 'DESC';
                $line = 'SUBSTRING(' . $field . ', ' . $start . ', ' . $length . ') ' . $direction;
                $this->builder->orderByRaw($line);
            }
        }
    }

    public function applyDefaultOrderBy()
    {
        $list = $this->details::defaultOrderBy();

        foreach ($list as $field => $direction) {
            $this->builder->orderBy($field, $direction === 'asc' ? 'asc' : 'desc');
        }
    }
}
