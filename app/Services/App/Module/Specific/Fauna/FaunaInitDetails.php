<?php

namespace App\Services\App\Module\Specific\Fauna;

use App\Services\App\Module\InitDetailsInterface;

class FaunaInitDetails implements InitDetailsInterface
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
            'This module displays the fauna remains recovered from the site.',
        ];
    }

    public static function modelGroups(): array
    {
        return [
            'Season' => [
                'code' => 'FO',
                'source' => ['module' => 'Season', 'field' => 'id'],
                'field_name' => 'locus_id',
                'dependency' => [],
                'manipulator' => function ($val) {
                    return (string) ($val + 10);
                },
            ],
            'Area' => [
                'code' => 'FO',
                'source' => ['module' => 'Area', 'field' => 'id'],
                'field_name' => 'area_id',
                'dependency' => [],
            ],
            'Registration Code' => [
                'code' => 'FO',
                'source' => ['module' => 'Fauna', 'field' => 'code'],
                'field_name' => 'code',
                'dependency' => [],
            ],
            'Taxa' => [
                'code' => 'LV',
                'field_name' => 'primary_taxon_id',
                'lookup_table_name' => 'fauna_primary_taxa',
                'lookup_text_field' => 'name',
                'dependency' => [],
            ],
            'Mammal' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Bird' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Life-Stage' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Symmetry' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Fusion' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Breakage' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'D&R' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Weathering' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Bone-Name' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Tooth-Name' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Tooth-Age' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Tooth-Wear' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],


            ///
            'Search-Taxa' => [
                'label' => 'Search-Taxa',
                'code' => 'SF',
                'field_name' => 'taxa',
                'options' => [],
            ],
            'Order By' => [
                'code' => 'OB',
                'options' => ['Area', 'Season', 'Locus No.', 'Basket No.', 'Artifact No.'],
            ],
        ];
    }

    public static function categories(): array
    {
        return [
            'Registration' => [
                'Season',
                'Area',
                'Registration Code',
                'Media',
            ],
            "Elements" => [
                'Bone-Name',
                'Tooth-Name',
                'Tooth-Age',
                'Tooth-Wear'
            ],
            "Taxa" => [
                'Taxa',
                'Mammal',
                'Bird'
            ],
            // "Charactaristics" => [
            //     'Life-Stage',
            //     'Symmetry',
            //     'Fusion',
            //     'Breakage',
            //     'D&R',
            //     'Weathering'
            // ],
            'Search' => [
                'Search-Taxa',
            ],
            'Order By' => [
                'Order By',
            ],
        ];
    }
}
