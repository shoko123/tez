<?php

namespace App\Services\App\Module\Specific\Locus;

use Illuminate\Database\Eloquent\Builder;

use App\Services\App\Module\ReadDetailsInterface;


class LocusReadDetails implements ReadDetailsInterface
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
                'date_opened',
                'description',
                'deposit',
                'registration_notes'
            ]
        ];
    }

    public static function galleryPage(): array
    {
        return ['id', 'description'];
    }

    public static function relatedModules(string $id): array
    {
        $lr = new LocusRelated();
        return $lr->relatedModules($id);
    }
}
