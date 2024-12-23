<?php

namespace App\Services\App\Module\Specific\Glass;

use Illuminate\Database\Eloquent\Builder;

use App\Services\App\Module\ReadDetailsInterface;
use App\Services\App\Related\SmallFindsRelatedService;

class GlassReadDetails implements ReadDetailsInterface
{
    public static function applyCategorizedFilters(Builder $builder, array $groups): Builder
    {
        return $builder;
    }

    public static function defaultOrderBy(): array
    {
        return ['id' => 'asc'];
    }

    public static function tabularPage(): array
    {
        return [
            'fields' => [
                'id',
                'date_retrieved',
                'field_description',
                'field_notes',
                'description'
            ],
            'lookups' => [
                'glass_primary_classification_id' => 'primaryClassification'

            ]
        ];
    }

    public static function galleryPage(): array
    {
        return ['id', 'description'];
    }

    public static function relatedModules(string $id): array
    {
        $sf = new SmallFindsRelatedService();
        return $sf->relatedModules(substr($id, 0, 5));
    }
}
