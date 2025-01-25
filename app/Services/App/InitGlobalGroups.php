<?php

namespace App\Services\App;

class InitGlobalGroups
{
    public static function groups(): array
    {
        return [
            'Season' => [
                'code' => 'RV',
                'values_source_module' => 'Season',
                'values_source_field' => 'id',
            ],
            'Area' => [
                'code' => 'RV',
                'values_source_module' => 'Area',
                'values_source_field' => 'id',
            ],
            'Registration Scope' => [
                'code' => 'CT',
                'option_labels'  => ['Basket', 'Artifact']
            ],
            'Media' => [
                'code' => 'MD',
                'options' => [], // loaded to front end at appInit()
            ],
            'Periods' => [
                'code' => 'TG',
                'dependency' => [],
            ],
            'Neolithic Subperiods' => [
                'code' => 'TG',
                'dependency' => [['Periods:Neolithic']],
            ],
            'Bronze Subperiods' => [
                'code' => 'TG',
                'dependency' => [['Periods:Bronze']],
            ],
            'Iron Subperiods' => [
                'code' => 'TG',
                'dependency' => [['Periods:Iron']],
            ],
            'Hellenistic Subperiods' => [
                'code' => 'TG',
                'dependency' => [['Periods:Hellenistic']],
            ],
            'Roman Subperiods' => [
                'code' => 'TG',
                'dependency' => [['Periods:Roman']],
            ],
            'Early-Islamic Subperiods' => [
                'code' => 'TG',
                'dependency' => [['Periods:Early Islamic']],
            ],
            'Medieval Subperiods' => [
                'code' => 'TG',
                'dependency' => [['Periods:Medieval']],
            ],
            'Modern Subperiods' => [
                'code' => 'TG',
                'dependency' => [['Periods:Modern']],
            ],
        ];
    }
}
