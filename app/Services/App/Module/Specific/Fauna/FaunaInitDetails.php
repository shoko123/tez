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
            'Primary Taxa' => [
                'code' => 'LV',
                'field_name' => 'primary_taxon_id',
                'lookup_table_name' => 'fauna_primary_taxa',
                'lookup_text_field' => 'name',
                'dependency' => [],
            ],
            'Fauna Scope' => [
                'code' => 'LV',
                'field_name' => 'scope_id',
                'lookup_table_name' => 'fauna_scopes',
                'lookup_text_field' => 'name',
                'dependency' => [],
            ],
            'Material' => [
                'code' => 'LV',
                'field_name' => 'material_id',
                'lookup_table_name' => 'fauna_materials',
                'lookup_text_field' => 'name',
                'dependency' => [['Fauna Scope:Single Item', 'Fauna Scope:Anatomical Cluster', 'Fauna Scope:Skeleton']],
            ],
            'Integumentary Material' => [
                'code' => 'TM',
                'dependency' => [['Material:Integumentary']],
                'multiple' => true,
            ],
            'Mammal Taxa' => [
                'code' => 'TM',
                'dependency' => [['Primary Taxa:Mammal']],
                'multiple' => true,
            ],
            'Bird Taxa' => [
                'code' => 'TM',
                'dependency' => [['Primary Taxa:Bird']],
                'multiple' => true,
            ],
            'Common Bone' => [
                'code' => 'TM',
                'dependency' => [['Fauna Scope:Single Item'], ['Material:Bone', 'Material:Bone and Tooth']],
                'multiple' => true,
            ],
            'Mammal Bone' => [
                'code' => 'TM',
                'dependency' => [['Material:Bone'], ['Primary Taxa:Mammal']],
                'multiple' => true,
            ],
            'Bird Bone' => [
                'code' => 'TM',
                'dependency' => [['Material:Bone'], ['Primary Taxa:Bird']],
                'multiple' => true,
            ],
            'Symmetry' => [
                'code' => 'TM',
                'dependency' => [['Fauna Scope:Single Item'], ['Material:Bone']],
                'multiple' => true,
            ],
            'Fusion' => [
                'code' => 'TM',
                'dependency' => [['Fauna Scope:Single Item'], ['Material:Bone']],
                'multiple' => true,
            ],
            'Breakage' => [
                'code' => 'TM',
                'dependency' => [['Fauna Scope:Single Item'], ['Material:Bone']],
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
            "Basic Characteristics" => [
                'Primary Taxa',
                'Fauna Scope',
                'Material'
            ],
            "Taxa" => [
                'Mammal Taxa',
                'Bird Taxa'
            ],
            "Integumentary" => [
                'Integumentary Material'
            ],
            "Bone" => [
                'Common Bone',
                'Mammal Bone',
                'Bird Bone'
            ],
            "Bone Charactaristics" => [
                'Symmetry',
                'Fusion',
                'Breakage',
                // 'Weathering'
            ],
            'Search' => [
                'Search-Taxa',
            ],
            'Order By' => [
                'Order By',
            ],
        ];
    }
}
