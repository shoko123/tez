<?php

namespace App\Services\App\Module\Specific\Survey;

use App\Services\App\Module\InitDetailsInterface;

class SurveyInitDetails implements InitDetailsInterface
{
    public static function displayOptions(): array
    {
        return [
            'item_views' => ['Main', 'Media', 'Related'],
            'collection_views' => (object)[
                'main' => ['Gallery', 'Tabular', 'Chips'],
                'related' => ['Gallery', 'Tabular', 'Chips'],
                'media' => ['Gallery'],
            ],
            'items_per_page' => ['Gallery' => 36, 'Tabular' => 50, 'Chips' => 100]
        ];
    }

    public static function welcomeText(): array
    {
        return [
            "This module describes the features documented in the survey season of summer 2012.",
        ];
    }

    public static function modelGroups(): array
    {
        return [
            'Search-Description' => [
                'code' => 'SF',
                'field_name' => 'description',
            ],
            'Order By' => [
                'label' => 'Order By',
                'code' => 'OB',
                'options' => ['Area', 'Feature No.'],
            ],
        ];
    }

    public static function categories(): array
    {
        return [
            'Registration' => [
                'Area',
                'Media'
            ],
            'Search' => [
                'Search-Description',
            ],
            'Order By' => [
                'Order By',
            ],
        ];
    }
}
