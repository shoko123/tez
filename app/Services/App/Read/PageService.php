<?php

namespace App\Services\App\Read;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

use App\Services\App\DigModuleService;
use App\Services\App\Utils\GetService;
use App\Services\App\Module\ReadDetailsInterface;
use App\Models\Module\DigModuleModel;
use App\Services\App\MediaService;


class PageService extends DigModuleService
{
    protected DigModuleModel $model;

    protected Builder $builder;

    protected ReadDetailsInterface $details;

    // Used by tabular to define columns collected and lookup values
    protected $selectArr = [];
    protected  $withArr = [];
    protected  $lookups = [];

    public function __construct(string $module)
    {
        parent::__construct($module);
        $this->details = GetService::getDetails('Read', $module);
    }


    public function page(array $ids, string $view): array
    {
        // Build query according to view
        if ($view === 'Tabular') {
            $this->buildTabularQuery();
        } else {
            $this->buildGalleryQuery();
        }

        // Limit to given ids
        $this->builder = $this->builder->whereIn('id', $ids);

        //order by given (string) ids
        $sortedIds = "'" . implode("', '", $ids) . "'";
        $this->builder->orderByRaw("FIELD(id, {$sortedIds})");

        // Retreive from DB results
        $res = $this->builder->get();

        // Return formatted results
        $response = $view === 'Tabular' ? $this->formatTabularResult($res) : $this->formatGalleryResult($res);
        return $response->toArray();
    }

    // Tabular
    public function buildTabularQuery()
    {
        $this->parseTabularFields();

        $this->builder = $this->model
            ->select($this->selectArr)
            ->with($this->withArr);
    }

    public function parseTabularFields()
    {
        foreach ($this->details::fieldsForTabularPage() as $key => $data) {
            if (is_array($data)) {
                array_push($this->withArr, array_values($data)[0]);
                array_push($this->selectArr, array_keys($data)[0]);
                array_push($this->lookups, $data);
            } else {
                array_push($this->selectArr, $data);
            }
        }
    }


    // Awkwardly replace lookup FK with value
    public function formatTabularResult(Collection $res)
    {
        return $res->map(function (object $rec) {
            $newRec = clone $rec;
            foreach ($this->lookups as $lookup) {
                foreach ($lookup as $key => $val) {
                    $newRec->$key = $newRec->$val->name;
                    unset($newRec->$val);
                }
            }
            return $newRec;
        });
    }

    // Gallery
    public function buildGalleryQuery()
    {
        $this->builder = $this->model->select($this->details::fieldsForGalleryPage())
            ->with(['media' => function ($query) {
                $query->orderBy('order_column')->limit(1);
            }]);
    }

    public function formatGalleryResult(Collection $res)
    {
        return $res->map(function ($item, $key) {
            return [
                'id' => $item['id'],
                'short' => $item['short'],
                'urls' => $item->media->isEmpty() ? null :
                    MediaService::format_media_item($item->media[0])['urls'],
            ];
        });
    }
}
