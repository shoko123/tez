<?php

namespace App\Services\App\Read;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\GeneralJsonException;
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
    protected $withArr = [];
    protected $lookups = [];
    protected $onps = [];
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

        // Retreive from DB results
        $res = $this->builder->get();

        // Return formatted results
        $response = $view === 'Tabular' ? $this->formatTabularResult($res) : $this->formatGalleryResult($res);
        if ($view === 'Tabular') {
            // dd($response);
        }
        return $response->toArray();
    }

    // Tabular
    public function buildTabularQuery()
    {
        $this->parseTabularFields();
        // dd('Query select: ' . print_r($this->selectArr) . '\nwith: ' . print_r($this->withArr));
        $this->builder = $this->model
            ->select()
            ->with($this->withArr);
    }

    public function parseTabularFields()
    {
        // dd($this->details::tabularPage());
        foreach ($this->details::tabularPage() as $cat => $data) {
            switch ($cat) {
                case 'fields':
                    foreach ($data as $key => $field_name) {
                        array_push($this->selectArr, $field_name);
                    }
                    break;

                case 'lookups':
                    foreach ($data as $lu_val => $access) {
                        array_push($this->withArr, $access);
                        array_push($this->selectArr, $lu_val);
                        array_push($this->lookups, $lu_val);
                    }
                    break;
                case 'onps':
                    // array_push($this->withArr, 'onps.onp_group');
                    break;
                default:
                    throw new GeneralJsonException('tabularPage() bad category: ' . $cat, 422);
            }
        }

        // foreach ($this->details::tabularPage() as $key => $data) {
        //     if (is_array($data)) {
        //         array_push($this->withArr, array_values($data)[0]);
        //         array_push($this->selectArr, array_keys($data)[0]);
        //         array_push($this->lookups, $data);
        //     } else {
        //         array_push($this->selectArr, $data);
        //     }
        // }
    }


    // Awkwardly replace lookup FK with value
    public function formatTabularResult(Collection $res)
    {
        $this->tabularPageDetails = $this->details::tabularPage();

        return $res->map(function (object $rec) {
            return $this->formatTabularRecord($rec);
        });
    }

    public function formatTabularRecord(object $res)
    {
        $formatted = [];
        foreach ($this->tabularPageDetails['fields'] as $key => $data) {
            $formatted[$data] = $res->$data;
        }

        if (array_key_exists('lookups', $this->tabularPageDetails)) {
            foreach ($this->tabularPageDetails['lookups'] as $key => $data) {
                $formatted[$key] = $res->$data['name'];
            }
        }

        if (array_key_exists('onps', $this->tabularPageDetails)) {

            $all = $res->onps->reduce(function (?string $carry, object $item) {
                return $carry .= $item['label'] . ',\n';
            });

            $formatted['onps'] = $all;
        }
        // dd($res);

        return $formatted;

        /////////////////
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
