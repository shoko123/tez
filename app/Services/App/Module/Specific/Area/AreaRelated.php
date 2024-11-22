<?php

namespace App\Services\App\Module\Specific\Area;

use Illuminate\Database\Eloquent\Collection;

use App\Services\App\DigModuleService;
use App\Services\App\Utils\FormatDbResult;

class AreaRelated extends DigModuleService
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

    private function accessDb(string $id): Collection
    {
        return $this->model->with([
            'media' => function ($query) {
                $query->orderBy('order_column')->limit(1);
            },
        ])
            ->where('area_id', $id)
            ->get();
    }

    private function formatResponse($recs): array
    {
        return FormatDbResult::transformArrayOfItems('Has Locus', 'Locus', $recs);
    }
}
