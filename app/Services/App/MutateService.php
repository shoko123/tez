<?php

namespace App\Services\App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Exception;
use App\Exceptions\GeneralJsonException;
use App\Services\App\Utils\GetService;

class MutateService extends DigModuleService
{
    private bool $isCreate;

    public function __construct(string $module)
    {
        parent::__construct($module);
    }

    public function create(array $data): array
    {
        $this->isCreate = true;
        return $this->save($data);
    }

    public function update(array $data): array
    {
        $this->isCreate = false;
        $with_arr = $this->uses_onps() ? [
            'onps' => function ($query) {
                $query->select('id');
            }
        ] : [];
        $this->model = $this->model
            ->with($with_arr)
            ->findOrFail($data['fields']['id']);

        return $this->save($data);
    }

    protected function save(array $data): array
    {
        $onps = [];

        DB::transaction(function () use ($data, &$onps) {
            $this->saveFields($data['fields']);
            if ($this->uses_onps()) {
                $onps = $this->syncOnps(collect($data['onps']));
            }
        });

        return [
            'existing_onp' => $this->model->onps,
            'fields' => $this->model->makeHidden(['onps']),
            'onps' => $onps
        ];
    }

    protected function syncOnps(Collection $sync): array
    {
        $attach = collect([]);
        $detach_ids = collect([]);
        $update = collect([]);

        if ($this->isCreate) {
            $attach = $sync;
        } else {
            // get sync ids
            $sync_ids = $sync->map(function (array $item) {
                return $item['id'];
            });

            // format current
            $current = $this->model->onps
                ->map(function (object $wp) {
                    return ['id' => $wp['id'], 'value' => $wp['pivot']['value']];
                });

            // get current ids
            $current_ids = $current->map(function (array $item) {
                return $item['id'];
            });

            // extract attach/detach ids
            $attach_ids = $sync_ids->diff($current_ids)->values()->all();
            $detach_ids = collect($current_ids)->diff($sync_ids)->values()->all();

            // format attach array
            $attach = collect($attach_ids)->map(function (int $id) use ($sync) {
                $item = $sync->first(function (array $value) use ($id) {
                    return $value['id'] === $id;
                });
                return $item;
            });

            // get/format onps that require value update
            $update = $sync->filter(function (array $syn) use ($current) {
                $item = $current->first(function (array $curr) use ($syn) {
                    return $syn['id'] === $curr['id'];
                });
                return $item && $item['value'] !== $syn['value'];
            });
        }

        //save changes
        //------------

        // detach
        $this->model->onps()->detach($detach_ids);

        // attach
        $attach->each(function (array $item) {
            $this->model->onps()->attach([$item['id'] => ['value' => $item['value']]]);
        });

        // update value for existing pivot entries
        $update->each(function (array $item) {
            $this->model->onps()->updateExistingPivot($item['id'], ['value' => $item['value']]);
        });

        // debug
        return [
            'detach_ids' => $detach_ids,
            'attach' => $attach,
            'update' => $update
        ];
    }

    protected function saveFields(array $fields)
    {
        //copy the validated data from the validated array to the 'item' object.
        foreach ($fields as $key => $value) {
            $this->model[$key] = $value;
            // if (str_contains($key, '_date') /* && strtotime($value) !== false */) {
            //     $this->model[$key] = Carbon::parse($value)->format('Y-m-d');
            // } else {
            //     $this->model[$key] = $value;
            // }
        }

        // dd('Fields: ' . $this->model);

        if ($this->model->derivedId !== $this->model->id) {
            throw new GeneralJsonException('Unable to save d/t inconsistency between id: "' . $this->model->id . '" and derived id: ' . $this->model->derivedId, 422);
        }
        try {
            $this->model->save();
        } catch (Exception $e) {
            throw new GeneralJsonException($e->getMessage() . $e->getCode());
        }
    }



    public function destroy(string $module, string $id): array
    {
        //get item with tags
        $model = GetService::getModel($module, true);

        $item = $this->model->with(['module_tags', 'global_tags'])->findOrFail($id);
        DB::transaction(function () use ($item) {
            $item->module_tags()->detach();
            $item->global_tags()->detach();
            $item->delete();
        });

        unset($item->module_tags);
        unset($item->global_tags);

        return ["deleted_id" => $item->id];
    }
}
