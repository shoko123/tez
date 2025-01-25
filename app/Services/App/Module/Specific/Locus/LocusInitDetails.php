<?php

namespace App\Services\App\Module\Specific\Locus;

use App\Services\App\Module\InitDetailsInterface;

class LocusInitDetails  implements InitDetailsInterface
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
            'This module displays the loci from the six excavation seasons (2013-2018).',
        ];
    }

    public static function modelGroups(): array
    {
        return [
            'Search-Description' => [
                'label' => 'Search-Description',
                'code' => 'SF',
                'field_name' => 'description',
            ],
            'Square' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Locus Type' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Order By' => [
                'code' => 'OB',
                'options' => ['Season', 'Area', 'Locus No.'],
            ],
        ];
    }

    public static function categories(): array
    {
        return [
            'Registration' => [
                'Season',
                'Area',
                'Media',
                'Square',
            ],
            'Description' => [
                'Locus Type'
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
