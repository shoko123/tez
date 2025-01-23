<?php

namespace App\Services\App\Module\Specific\Stone;

use App\Services\App\Module\InitDetailsInterface;

class StoneInitDetails implements InitDetailsInterface
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
            'This module displays the large and diverse collection of stone artifacts found at the site.
            While most are ground stone tools, this assemblage also includes small finds like slingstones, scaraboid seals, and tesserae and larger finds like standing stones and architectural fragments.',
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
            'Material' => [
                'code' => 'LV',
                'field_name' => 'material_id',
                'lookup_table_name' => 'stone_materials',
                'lookup_text_field' => 'name',
                'dependency' => [],
            ],
            'Primary Classification' => [
                'code' => 'LV',
                'field_name' => 'stone_primary_classification_id',
                'lookup_table_name' => 'stone_primary_classifications',
                'lookup_text_field' => 'name',
                'dependency' => [],
            ],
            'Life-Stage' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Morphology' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Profile' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Production' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Use-Wear' => [
                'code' => 'TM',
                'dependency' => [],
                'multiple' => true,
            ],
            'Type-Passive' => [
                'code' => 'TM',
                'dependency' => [['Primary Classification:Passive']],
                'multiple' => true,
            ],
            'Type-Active' => [
                'code' => 'TM',
                'dependency' => [['Primary Classification:Active (handheld)']],
                'multiple' => true,
            ],
            // 'Vessel Types' => [
            //     'code' => 'TM',
            //     'dependency' => ['Basic Typology:Vessel'],
            //     'multiple' => true,
            // ],
            'Vessel-Part' => [
                'code' => 'TM',
                'dependency' => [['Primary Classification:Vessel']],
                'multiple' => true,
            ],
            'Vessel-Base' => [
                'code' => 'TM',
                'dependency' => [['Primary Classification:Vessel']],
                'multiple' => true,
            ],
            'Vessel-Wall' => [
                'code' => 'TM',
                'dependency' => [['Primary Classification:Vessel']],
                'multiple' => true,
            ],
            'Vessel-Rim' => [
                'code' => 'TM',
                'dependency' => [['Primary Classification:Vessel']],
                'multiple' => true,
            ],
            'Type-Non-Processor' => [
                'code' => 'TM',
                'dependency' => [['Primary Classification:Non-Processor']],
                'multiple' => true,
            ],
            'Search-ID' => [
                'label' => 'Search-ID',
                'code' => 'SF',
                'field_name' => 'id',
            ],
            'Order By' => [
                'code' => 'OB',
                'options' => ['Season', 'Area',  'Locus No.']
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
            'Search' => [
                'Search-ID',
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
            'Basic Characteristics' => [
                'Material',
                'Life-Stage',
                'Morphology',
                'Use-Wear',
                'Profile',
                'Production',
                'Primary Classification',
            ],
            'Typology' => [
                'Type-Passive',
                'Type-Active',
                'Vessel-Part',
                'Vessel-Base',
                'Vessel-Wall',
                'Vessel-Rim',
                'Type-Non-Processor',
            ],
            'Order By' => [
                'Order By',
            ],
        ];
    }
}
