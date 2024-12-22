<?php

namespace App\Services\App\Module\Specific\Fauna;

use Illuminate\Database\Eloquent\Builder;

use App\Services\App\Module\ReadDetailsInterface;
use App\Services\App\Related\SmallFindsRelatedService;

class FaunaReadDetails implements ReadDetailsInterface
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
        return ['id', 'date_retrieved', ['fauna_taxon_id' => 'taxon'], ['fauna_element_id' => 'element'], 'description', 'notes'];
    }

    public static function fieldsForGalleryPage(): array
    {
        return ['id', 'description'];
    }

    public static function relatedModules(string $id): array
    {
        $sf = new SmallFindsRelatedService();
        return $sf->relatedModules(substr($id, 0, 5));
    }
}
