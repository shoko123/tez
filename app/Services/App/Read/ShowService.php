<?php

namespace App\Services\App\Read;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use App\Services\App\DigModuleService;
use App\Services\App\Utils\GetService;
use App\Services\App\Module\ReadDetailsInterface;
use App\Models\Module\DigModuleModel;
use App\Services\App\MediaService;

class ShowService extends DigModuleService
{
    protected DigModuleModel $model;
    protected Builder $builder;
    protected ReadDetailsInterface $details;

    public function __construct(string $module)
    {
        parent::__construct($module);
        $this->details = GetService::getDetails('Read', $module);
    }

    public function show_carousel(string $module, string $id): array
    {
        $mediaCollection = MediaService::media_by_module_and_id($module, $id);

        $model = GetService::getModel($module, true);
        $item = $model->findOrfail($id);

        return [
            'id' => $item['id'],
            'short' => $item['short'],
            'urls' => count($item->media) === 0 ? null : $mediaCollection[0]['urls'],
            'module' => $module,
        ];
    }

    public function show(string $id): array
    {
        $this->applyShowLoad();
        $item = $this->builder->findOrFail($id);
        $related = $this->details->relatedModules($id);
        return $this->formatShowResponse($item, $related);
    }

    protected function applyShowLoad(): void
    {
        $with_arr = collect([
            'media' => function ($query) {
                $query->orderBy('order_column');
            },
        ]);

        if ($this->uses_tags()) {
            $with_arr->push('module_tags.tag_group');
            $with_arr->push('global_tags.tag_group');
        }
        if ($this->uses_onps()) {
            $with_arr->push('onps');
        }

        $this->builder = $this->model->with($with_arr->toArray());
    }

    protected function formatShowResponse(object $item, array $related): array
    {
        $mediaArray = MediaService::format_media_collection($item->media);

        //model tags (discrete)
        $module_tags = isset($item['module_tags']) ? $item->module_tags->map(function ($tag, int $key) {
            return ['group_label' => $tag->tag_group->name, 'tag_text' => $tag->name];
        }) : [];

        //global tags
        $global_tags = isset($item['global_tags']) ? $item->global_tags->map(function ($tag, int $key) {
            return ['group_label' => $tag->tag_group->name, 'tag_text' => $tag->name];
        }) : [];

        $onps = isset($item['onps']) ? $item->onps->map(function ($onp, int $key) {
            return ['group_label' => $onp->group_label, 'label' => $onp->label, 'value' => $onp->pivot->value];
        }) : [];

        return [
            'fields' => $item->makeHidden(['short', 'media', 'module_tags', 'global_tags', 'onps']),
            'media' => $mediaArray,
            'global_tags' => $global_tags,
            'module_tags' => $module_tags,
            'short' => $item->short,
            'onps' => $onps,
            'related' =>  $related
        ];
    }
}
