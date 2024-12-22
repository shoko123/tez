<?php

namespace App\Services\App\Utils;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FormatDbResult
{
    public static function transformOneItem(string $relation_name, string $module, object $rec): array
    {
        $item = [
            'relation_name' => $relation_name,
            'module' => $module,
            'id' => $rec->id,
            'short' => $rec->short,
        ];

        if (count($rec->media) > 0) {
            $item['urls'] = self::mediaToUrls($rec->media[0]);
        }
        return $item;
    }

    public static function transformArrayOfItems(string $relation_name, string $module, Collection $recs): array
    {
        $all = [];
        foreach ($recs as $rec) {
            $one = self::transformOneItem($relation_name, $module, $rec);
            array_push($all, $one);
        }
        return $all;
    }

    public static function mediaToUrls(Media $media): array
    {
        return ['full' => $media->getPath(), 'tn' => $media->getPath('tn')];
    }
}
