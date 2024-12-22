<?php

namespace App\Services\App\Module\Specific\Season;

use App\Services\App\Module\InitDetailsInterface;

class SeasonInitDetails   implements InitDetailsInterface
{
    public static function displayOptions(): array
    {
        return [
            'item_views' => ['Main', 'Media', 'Related'],
            'collection_views' => (object)[
                'main' => ['Gallery', 'Tabular', 'Chips'],
                'related' => ['Tabular', 'Gallery', 'Chips'],
                'media' => ['Gallery'],
            ],
            'items_per_page' => ['Gallery' => 36, 'Tabular' => 50, 'Chips' => 100]
        ];
    }

    public static function welcomeText(): array
    {
        return [
            'This module describes the Jezreel Expedition\'s 2012 survey season and 2013-2018 excavation seasons.',
        ];
    }

    public static function modelGroups(): array
    {
        return [];
    }

    public static function categories(): array
    {
        return [];
    }
}
