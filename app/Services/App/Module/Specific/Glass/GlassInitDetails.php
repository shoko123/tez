<?php

namespace App\Services\App\Module\Specific\Glass;

use App\Services\App\Module\InitDetailsInterface;

class GlassInitDetails implements InitDetailsInterface
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
            'This module displays the glass artifacts found at the site.',
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
            'Primary Classification' => [
                'code' => 'LV',
                'field_name' => 'glass_primary_classification_id',
                'lookup_table_name' => 'glass_primary_classifications',
                'lookup_text_field' => 'name',
                'dependency' => [],
            ],
            'Production' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Vessel-Subtype' => [
                'code' => 'TM',
                'dependency' => ['Primary Classification.Vessel/Lamp'],
                'multiple' => true,
            ],
            'Color' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Weathering' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Weathering-Type' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
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
            "Charactaristics" => [
                'Color',
                'Production',
                'Weathering',
                'Weathering-Type'
            ],
            "Typology" => [
                'Primary Classification',
                'Vessel-Subtype'
            ],
            'Periods' => [
                'Periods',
                'Neolithic Subperiods',
                'Bronze Subperiods',
                'Iron Subperiods',
                'Hellenistic Subperiods',
                'Roman Subperiods',
                'Early-Islamic Subperiods',
                'Medieval Subperiods',
                'Modern Subperiods',
            ],
            'Order By' => [
                'Order By',
            ],
        ];
    }
}
