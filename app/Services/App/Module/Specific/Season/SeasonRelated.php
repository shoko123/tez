<?php

namespace App\Services\App\Module\Specific\Season;

use Illuminate\Database\Eloquent\Collection;

use App\Services\App\DigModuleService;
use App\Services\App\Utils\FormatDbResult;

class SeasonRelated extends DigModuleService
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
            ->where('season_id', $id)
            ->get();
    }

    private function formatResponse($recs): array
    {
        return FormatDbResult::transformArrayOfItems('Has Locus', 'Locus', $recs);
    }
}
