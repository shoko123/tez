<?php

namespace App\Services\App\Module\Specific\Lithic;

use App\Services\App\Module\InitDetailsInterface;

class LithicInitDetails implements InitDetailsInterface
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
            'This module displays the chipped and flaked stone tools found by the Jezreel Expedition.',
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
                'source' => ['module' => 'Lithic', 'field' => 'code'],
                'field_name' => 'code',
                'dependency' => [],
            ],
            // 'Primary Classification' => [
            //     'code' => 'LV',
            //     'field_name' => 'lithic_primary_classification_id',
            //     'lookup_table_name' => 'lithic_primary_classifications',
            //     'lookup_text_field' => 'name',
            //     'dependency' => ['Scope.Artifact'],
            // ],

            'Types' => [
                'code' => 'ON',
            ],
            'Includes Date' => [
                'label' => 'Includes Date',
                'code' => 'CT',
                'options'  => [['label' => 'Yes', 'index' => 0], ['label' => 'No', 'index' => 1]]
            ],
            'Search-Description' => [
                'label' => 'Search-Description',
                'code' => 'SF',
                'field_name' => 'description',
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
                'Scope',
                'Media',
            ],
            "Typology" => [
                'Types',
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
