<?php

namespace App\Services\App\Module\Specific\Area;

use Illuminate\Database\Eloquent\Builder;

use App\Services\App\Module\ReadDetailsInterface;


class AreaReadDetails implements ReadDetailsInterface
{
    public static function applyCategorizedFilters(Builder $builder, array $groups): Builder
    {
        return $builder;
    }

    public static function defaultOrderBy(): array
    {
        return ['id' => 'asc'];
    }

    public static function fieldsForTabularPage(): array
    {
        return ['id', 'description', 'notes'];
    }

    public static function fieldsForGalleryPage(): array
    {
        return ['id', 'description'];
    }

    public static function relatedModules(string $id): array
    {
        $rs = new AreaRelated();
        return $rs->relatedModules($id);
    }
}
