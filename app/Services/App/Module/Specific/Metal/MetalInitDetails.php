<?php

namespace App\Services\App\Module\Specific\Metal;

use App\Services\App\Module\InitDetailsInterface;

class MetalInitDetails   implements InitDetailsInterface
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
            'This module displays the metal artifacts found at the site.',
        ];
    }

    public static function modelGroups(): array
    {
        return [
            'Season' => [
                'code' => 'RV',
                'source' => ['module' => 'Season', 'field' => 'id'],
                'field_name' => 'locus_id',
                'dependency' => [],
                'manipulator' => function ($val) {
                    return (string) ($val + 10);
                },
            ],
            'Area' => [
                'code' => 'RV',
                'source' => ['module' => 'Area', 'field' => 'id'],
                'field_name' => 'area_id',
                'dependency' => [],
            ],
            'Registration Code' => [
                'code' => 'EM',
                'field_name' => 'code',
                'useInTagger' => false,
                'dependency' => [],
            ],
            'Primary Classification' => [
                'code' => 'LV',
                'field_name' => 'metal_primary_classification_id',
                'lookup_table_name' => 'metal_primary_classifications',
                'lookup_text_field' => 'name',
                'dependency' => [],
            ],
            'Material' => [
                'code' => 'LV',
                'field_name' => 'material_id',
                'lookup_table_name' => 'metal_materials',
                'lookup_text_field' => 'name',
                'dependency' => [],
            ],
            'Modern Weaponry' => [
                'code' => 'TM',
                'dependency' => [['Primary Classification:Modern Weaponry']],
                'multiple' => true,
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
                'Media',
            ],
            "Basic Charactaristics" => [
                'Material',
                'Primary Classification',
                'Modern Weaponry'
            ],
            'Periods' => [
                'Periods',
                'Bronze Subperiods',
                'Iron Subperiods',
                'Hellenistic Subperiods',
                'Roman Subperiods',
                'Early-Islamic Subperiods',
                'Medieval Subperiods',
                'Modern Subperiods',
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
