<?php

namespace App\Services\App\Module\Specific\Ceramic;

use App\Services\App\Module\InitDetailsInterface;

class CeramicInitDetails implements InitDetailsInterface
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
            'This module displays the indicative pottery excavated by the Jezreel Expedition organized by locus and basket. 
            The pottery assemblage also includes ceramic artifacts like figurines, andirons, and spindle whorls.'
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
                'source' => ['module' => 'Ceramic', 'field' => 'code'],
                'field_name' => 'code',
                'dependency' => [],
            ],
            'Primary Classification' => [
                'code' => 'LV',
                'field_name' => 'ceramic_primary_classification_id',
                'lookup_table_name' => 'ceramic_primary_classifications',
                'lookup_text_field' => 'name',
                'dependency' => [['Scope:Artifact']],
            ],
            'Includes Date' => [
                'label' => 'Includes Date',
                'code' => 'CT',
                'options'  => [['label' => 'Yes', 'index' => 0], ['label' => 'No', 'index' => 1]]
            ],
            'Search-Periods' => [
                'label' => 'Search-Periods',
                'code' => 'SF',
                'field_name' => 'periods',
                'options' => [],
            ],
            'Search-Description' => [
                'label' => 'Search-Description',
                'code' => 'SF',
                'field_name' => 'description',
                'options' => [],
            ],
            'Ware Coarseness' => [
                'code' => 'TM',
                'dependency' => [['Scope:Artifact']],
                'multiple' => true,
            ],
            'Ware Color' => [
                'code' => 'TM',
                'dependency' => [['Scope:Artifact']],
                'multiple' => true,
            ],
            'Ware Temper' => [
                'code' => 'TM',
                'dependency' => [['Scope:Artifact']],
                'multiple' => true,
            ],
            'Grit Color' => [
                'code' => 'TM',
                'dependency' => [['Scope:Artifact']],
                'multiple' => true,
            ],

            'Life Stage' => [
                'code' => 'TM',
                'dependency' => [['Scope:Artifact']],
                'multiple' => true,
            ],
            'Production' => [
                'code' => 'TM',
                'dependency' => [['Scope:Artifact']],
                'multiple' => true,
            ],
            'Vessel Typology' => [
                'code' => 'TM',
                'dependency' => [['Primary Classification:Vessel/Lid']],
                'multiple' => true,
            ],
            'Vessel Part' => [
                'code' => 'TM',
                'dependency' => [['Primary Classification:Vessel/Lid']],
                'multiple' => true,
            ],
            'Vessel Base' => [
                'code' => 'TM',
                'dependency' => [['Vessel Part:Base']],
                'multiple' => true,
            ],

            'Foot' => [
                'code' => 'TM',
                'dependency' => [['Vessel Part:Foot']],
                'multiple' => true,
            ],
            'Rim' => [
                'code' => 'TM',
                'dependency' => [['Vessel Part:Rim']],
                'multiple' => true,
            ],
            'Handle' => [
                'code' => 'TM',
                'dependency' => [['Vessel Part:Handle']],
                'multiple' => true,
            ],
            'Ceramic Artifact Typology' => [
                'code' => 'TM',
                'dependency' => [['Primary Classification:Ceramic Artifact']],
                'multiple' => true,
            ],
            'Architectural/Installation Typology' => [
                'code' => 'TM',
                'dependency' => [['Primary Classification:Architectural/Installation']],
                'multiple' => true,
            ],
            //// 
            'Plastic' => [
                'code' => 'TM',
                'dependency' => [['Scope:Artifact']],
                'multiple' => true,
            ],
            'Flat' => [
                'code' => 'TM',
                'dependency' => [['Scope:Artifact']],
                'multiple' => true,
            ],
            'Slip Color' => [
                'code' => 'TM',
                'dependency' => [['Flat:Slip']],
                'multiple' => true,
            ],
            'Paint Color' => [
                'code' => 'TM',
                'dependency' => [['Flat:Paint']],
                'multiple' => true,
            ],
            'Paint/Slip Pattern' => [
                'code' => 'TM',
                'dependency' => [['Flat:Paint', 'Flat:Slip']],
                'multiple' => true,
            ],
            'Named Groups' => [
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
                'Registration Code',
                'Scope',
                'Primary Classification',
                'Media',
                'Includes Date',
                'Search-Periods',
                'Search-Description',
            ],
            "Ware" => [
                'Ware Coarseness',
                'Ware Color',
                'Ware Temper',
                'Grit Color',
            ],
            'Processes' => [
                'Production',
                'Life Stage',
            ],
            'Surface Treatment' => [
                'Plastic',
                'Flat',
                'Slip Color',
                'Paint Color',
                'Paint/Slip Pattern',

            ],
            "Typology" => [
                'Vessel Typology',
                'Vessel Part',
                'Vessel Base',
                'Foot',
                'Rim',
                'Handle',
                'Ceramic Artifact Typology',
                'Architectural/Installation Typology'
            ],

            'Cultures/Periods' => [
                'Named Groups',
                'Periods',
                'Neolithic Subperiods',
                'Bronze Subperiods',
                'Iron Subperiods',
                'Hellenistic Subperiods',
                'Roman Subperiods',
                'Early-Islamic Subperiods',
                'Medieval Subperiods',
                'Modern Subperiods'
            ],
            // 'Ceramic Cultures' => [

            // ],
            'Order By' => [
                'Order By',
            ],
        ];
    }
}
