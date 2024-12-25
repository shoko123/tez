<?php

namespace App\Services\App\Module\Specific\Locus;

use Illuminate\Database\Eloquent\Model;

use App\Services\App\DigModuleService;
use App\Services\App\Utils\FormatDbResult;

class LocusRelated extends DigModuleService
{
    public function __construct()
    {
        parent::__construct('Locus');
    }

    public function relatedModules(string $id)
    {
        $res = $this->accessDb($id);
        return $this->formatResponse($res);
    }

    private function accessDb(string $id): Model
    {
        return $this->model->with([
            'area.media' => function ($query) {
                $query->orderBy('order_column')->limit(1);
            },
            'season.media' => function ($query) {
                $query->orderBy('order_column')->limit(1);
            },
            'ceramics.media' => function ($query) {
                $query->orderBy('order_column')->limit(1);
            },
            'fauna.media' => function ($query) {
                $query->orderBy('order_column')->limit(1);
            },
            'glass.media'
            => function ($query) {
                $query->orderBy('order_column')->limit(1);
            },
            'lithics.media'
            => function ($query) {
                $query->orderBy('order_column')->limit(1);
            },
            'metals.media' => function ($query) {
                $query->orderBy('order_column')->limit(1);
            },
            'stones.media' => function ($query) {
                $query->orderBy('order_column')->limit(1);
            },
        ])
            ->findOrfail($id);
    }

    static $smallFinds = ['ceramics'];

    private function formatResponse($res): array
    {
        $formatted = [];

        $small = ['ceramics' => 'Ceramic', 'stones' => 'Stone',  'lithics' => 'Lithic', 'fauna' => 'Fauna', 'glass' => 'Glass', 'metals' => 'Metal'];
        foreach ($small as $key => $val) {
            $list = FormatDbResult::transformArrayOfItems('Has Find', $val, $res->$key);
            $formatted = array_merge($formatted, $list);
        }

        $area_season = [
            FormatDbResult::transformOneItem('Belongs To', 'Area', $res->area),
            FormatDbResult::transformOneItem('Belongs To', 'Season', $res->season),
        ];
        return array_merge($formatted, $area_season);
    }
}
