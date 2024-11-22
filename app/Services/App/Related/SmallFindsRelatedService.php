<?php

namespace App\Services\App\Related;

use Illuminate\Database\Eloquent\Model;

use App\Services\App\DigModuleService;
use App\Services\App\Utils\FormatDbResult;

class SmallFindsRelatedService extends DigModuleService
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
            'media' => function ($query) {
                $query->orderBy('order_column')->limit(1);
            },
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
        $formatted = [
            FormatDbResult::transformOneItem('Belongs To', 'Locus', $res),
        ];

        $small = ['ceramics' => 'Ceramic', 'stones' => 'Stone',  'lithics' => 'Lithic', 'fauna' => 'Fauna', 'glass' => 'Glass', 'metals' => 'Metal'];
        foreach ($small as $key => $val) {
            $list = FormatDbResult::transformArrayOfItems('Locus find', $val, $res->$key);
            $formatted = array_merge($formatted, $list);
        }

        array_push($formatted, FormatDbResult::transformOneItem('Belongs To', 'Season', $res->season));
        array_push($formatted, FormatDbResult::transformOneItem('Belongs To', 'Area', $res->area));

        return $formatted;
    }
}
