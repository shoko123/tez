<?php

namespace App\Services\App\Module;

use Illuminate\Database\Eloquent\Builder;

interface ReadDetailsInterface
{
    public static function applyCategorizedFilters(Builder $builder, array $groups): Builder;

    public static function defaultOrderBy(): array;

    public static function tabularPage(): array;

    public static function galleryPage(): array;
    public static function relatedModules(string $id): array;
}
