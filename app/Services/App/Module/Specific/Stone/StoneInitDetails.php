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
            'Registration Code' => [
                'code' => 'EM',
                'field_name' => 'code',
                'useInTagger' => false,
                'showAsTag' => false,
                'dependency' => [],
            ],
            'Material' => [
                'code' => 'LV',
                'field_name' => 'material_id',
                'lookup_table_name' => 'stone_materials',
                'lookup_text_field' => 'name',
                'useInTagger' => true,
                'showAsTag' => true,
                'dependency' => [],
            ],
            'Primary Classification' => [
                'code' => 'LV',
                'field_name' => 'stone_primary_classification_id',
                'lookup_table_name' => 'stone_primary_classifications',
                'lookup_text_field' => 'name',
                'useInTagger' => true,
                'showAsTag' => true,
                'dependency' => [],
            ],
            'Life-Stage' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [],
            ],
            'Morphology' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [],
            ],
            'Profile' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [],
            ],
            'Production' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [],
            ],
            'Use-Wear' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [],
            ],
            'Type-Passive' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Primary Classification:Passive']],
            ],
            'Type-Active' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Primary Classification:Active (handheld)']],
            ],
            // 'Vessel Types' => [
            //     'code' => 'TM',
            //     'multiple' => true,            
            //     'dependency' => ['Basic Typology:Vessel'],
            // ],
            'Vessel-Part' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Primary Classification:Vessel']],
            ],
            'Vessel-Base' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Primary Classification:Vessel']],
            ],
            'Vessel-Wall' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Primary Classification:Vessel']],
            ],
            'Vessel-Rim' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Primary Classification:Vessel']],
            ],
            'Type-Non-Processor' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Primary Classification:Non-Processor']],
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
