<?php

namespace App\Services\App\Module\Specific\Ceramic;

use Illuminate\Database\Eloquent\Builder;

use App\Services\App\Module\ReadDetailsInterface;
use App\Services\App\Related\SmallFindsRelatedService;


class CeramicReadDetails implements ReadDetailsInterface
{
    private static Builder $builder;

    public static function applyCategorizedFilters(Builder $builder, array $groups): Builder
    {
        self::$builder = $builder;

        foreach ($groups as $key => $group) {
            switch ($group['group_name']) {
                case 'Registration Scope':
                    self::filterScope($group['selected']);
                    break;

                case 'Includes Date':
                    self::filterIncludesDate($group['selected']);
                    break;
                default:
                    // Do nothing
            }
        }
        return self::$builder;
    }

    private static function filterScope(array $vals)
    {
        if (count($vals) !== 1) {
            return;
        }

        self::$builder->Where(function ($query) use ($vals) {
            switch ($vals[0]['name']) {
                case 'Basket':
                    $query->where('artifact_no', 0);
                    return;

                case 'Artifact':
                    $query->Where('artifact_no', '!=', 0);
                    return;

                default:
                    return;
            }
        });
    }

    private static function filterIncludesDate(array $vals)
    {
        if (count($vals) !== 1) {
            return;
        }

        self::$builder->Where(function ($query) use ($vals) {
            switch ($vals[0]['name']) {
                case 'Yes':
                    $query->whereNotNull('date_retrieved');
                    return;

                case 'No':
                    $query->WhereNull('date_retrieved');
                    return;

                default:
                    return;
            }
        });
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
                'description',
                'periods'
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
