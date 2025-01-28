<?php

namespace App\Services\App\Read;

use Illuminate\Database\Eloquent\Builder;
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

    // Used by tabularPage to define the query and parse result
    private $selectArr = [];
    private $withArr = [];
    private $tabularPageDetails = [];

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

        // Retreive results from DB 
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
        // Copy once; use it to build query and parse result
        $this->tabularPageDetails = $this->details::tabularPage();

        foreach ($this->tabularPageDetails['fields'] as $key => $field_name) {
            array_push($this->selectArr, $field_name);
        }

        if (array_key_exists('lookups', $this->tabularPageDetails)) {
            foreach ($this->tabularPageDetails['lookups'] as $name => $access) {
                array_push($this->withArr, $access);
                array_push($this->selectArr, $name);
            }
        }

        if (array_key_exists('onps', $this->tabularPageDetails)) {
            array_push($this->withArr, 'onps');
        }
    }

    public function formatTabularResult(Collection $res)
    {
        return $res->map(function (object $rec) {
            return $this->formatTabularRecord($rec);
        });
    }

    public function formatTabularRecord(object $obj)
    {
        $formatted = [];
        foreach ($this->tabularPageDetails['fields'] as $key => $data) {
            $formatted[$data] = $obj->$data;
        }

        if (array_key_exists('lookups', $this->tabularPageDetails)) {
            foreach ($this->tabularPageDetails['lookups'] as $key => $data) {
                $formatted[$key] = $obj->$data['name'];
            }
        }

        if (array_key_exists('onps', $this->tabularPageDetails)) {
            $all = $obj->onps->reduce(function (?string $carry, object $item) {
                return $carry .= $item['label'] . '(' . $item['pivot']['value'] . '), ';
            });

            $formatted['onps'] =  substr($all, 0, -2);
        }
        return $formatted;
    }

    // Gallery
    public function buildGalleryQuery()
    {
        $this->builder = $this->model->select($this->details::galleryPage())
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
