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
            'Registration Code' => [
                'code' => 'EM',
                'field_name' => 'code',
                'useInTagger' => false,
                'showAsTag' => false,
                'dependency' => [],
            ],
            'Primary Classification' => [
                'code' => 'LV',
                'field_name' => 'primary_classification_id',
                'lookup_table_name' => 'ceramic_primary_classifications',
                'lookup_text_field' => 'name',
                'useInTagger' => true,
                'showAsTag' => true,
                'dependency' => [['Registration Scope:Artifact']],
            ],
            'Specialist' => [
                'code' => 'EM',
                'field_name' => 'specialist',
                'useInTagger' => false,
                'showAsTag' => false,
                'dependency' => [],
            ],
            'Includes Date' => [
                'code' => 'CT',
                'option_labels'  => ['Yes',  'No']
            ],
            'Search Periods' => [
                'code' => 'SF',
                'field_name' => 'periods',
                'options' => [],
            ],
            'Search Specialist-Description' => [
                'code' => 'SF',
                'field_name' => 'specialist_description',
                'options' => [],
            ],
            'Ware Coarseness' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Registration Scope:Artifact']],
            ],
            'Ware Color' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Registration Scope:Artifact']],
            ],
            'Ware Temper' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Registration Scope:Artifact']],
            ],
            'Grit Color' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Registration Scope:Artifact']],
            ],

            'Life Stage' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Registration Scope:Artifact']],
            ],
            'Production' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Registration Scope:Artifact']],
            ],
            'Vessel Typology' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Primary Classification:Vessel/Lid']],
            ],
            'Vessel Part' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Primary Classification:Vessel/Lid']],
            ],
            'Vessel Base' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Vessel Part:Base']],
            ],

            'Foot' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Vessel Part:Foot']],
            ],
            'Rim' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Vessel Part:Rim']],
            ],
            'Handle' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Vessel Part:Handle']],
            ],
            'Artifact Typology' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Primary Classification:Ceramic Artifact']],
            ],
            'Architectural/Installation Typology' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Primary Classification:Architectural/Installation']],
            ],
            //// 
            'Plastic' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Registration Scope:Artifact']],
            ],
            'Flat' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Registration Scope:Artifact']],
            ],
            'Slip Color' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Flat:Slip']],
            ],
            'Paint Color' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Flat:Paint']],
            ],
            'Paint/Slip Pattern' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [['Flat:Paint', 'Flat:Slip']],
            ],
            'Named Groups' => [
                'code' => 'TM',
                'multiple' => true,
                'dependency' => [],
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
                'Registration Code',
                'Registration Scope',
                'Season',
                'Area',
                'Media',
                'Specialist',
            ],
            "Typology" => [
                'Primary Classification',
            ],
            "Vessel Typology" => [
                'Vessel Typology',
                'Vessel Part',
                'Vessel Base',
                'Foot',
                'Rim',
                'Handle',
            ],
            "Artifact Typology" => [
                'Artifact Typology',
            ],
            "Architectural/Installation Typology" => [
                'Architectural/Installation Typology'
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
            'Search Text' => [
                'Search Periods',
                'Search Specialist-Description',
                'Includes Date',
            ],
            'Order By' => [
                'Order By',
            ],
        ];
    }
}
