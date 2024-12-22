<?php

namespace App\Services\App;

use App\Exceptions\GeneralJsonException;
use Exception;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Services\App\Utils\GetService;

class MediaService
{
    public static function collection_names()
    {
        $ordered_media_collection_names = collect(['Photo', 'Drawing', 'Photo and Drawing', 'Plan', 'Misc']);

        //verify that the table is not corrupted by some unwelcomed collection_name
        if (Media::count() > 0) {
            $names = Media::distinct('collection_name')->get();

            $res = $names->first(function (string $value, int $key) use ($ordered_media_collection_names) {
                return ! $ordered_media_collection_names->has($value);
            });

            throw_unless($res, 'MediaService.collection_names(): Collection name "' . $res . "\" doesn't exist in the the ordered collection names");
        }

        return $ordered_media_collection_names;
    }

    public static function show_carousel(string $media_id)
    {
        $media = Media::findOrFail($media_id);

        return array_merge(
            MediaService::format_media_item($media),
            [
                'size' => $media->size,
                'collection_name' => $media->collection_name,
                'file_name' => $media->file_name,
            ]
        );
    }

    public static function upload(string $module, string $id, array $media_files, string $media_collection_name): array
    {
        $model = GetService::getModel($module, true);
        try {
            $item = $model->findOrFail($id);

            //attach media to item
            foreach ($media_files as $key => $media_file) {
                //$meta = exif_read_data($media_file);
                $item
                    ->addMedia($media_file)
                    ->toMediaCollection($media_collection_name);
            }

            return static::media_by_module_and_id($module, $id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage() . $e->getCode());
        }
    }

    public static function destroy(string $media_id, string $module, string $module_id): array
    {
        //Get media record by media_id
        $mediaToDelete = Media::findOrFail($media_id);

        //verify that this media record matches item sent (by model_type and model_id)
        if (($mediaToDelete['model_type'] !== $module) || $mediaToDelete['model_id'] !== $module_id) {
            throw new GeneralJsonException('Media/Model mismatch abort destroy', 422);
        }

        //delete
        $mediaToDelete->delete();

        //return updated media for item
        return static::media_by_module_and_id($module, $module_id);
    }

    public static function reorder(string $module, string $module_id, array $ordered_media_ids): array
    {
        foreach ($ordered_media_ids as $index => $id) {
            $record = Media::findOrFail($id);
            if ($record['order_column'] !== $index) {
                $record['order_column'] = $index;
                $record->save();
            }
        }

        //return updated media for item
        return static::media_by_module_and_id($module, $module_id);
    }

    public static function edit(array $options): array
    {
        return [];
    }

    public static function media_by_module_and_id(string $module, string $id): array
    {
        $model = GetService::getModel($module, true);
        $item = $model->with(['media' => function ($query) {
            $query->orderBy('order_column');
        }])->findOrFail($id);

        return static::format_media_collection($item->media);
    }

    public static function format_media_collection(MediaCollection $media_records): array
    {
        $mapped = collect($media_records)->map(function ($rec, $key) {
            if ($rec instanceof Media) {
                return static::format_media_item($rec);
            } else {
                throw new GeneralJsonException('***MEDIA COLLECTION FORMAT ERROR***', 500);
            }
        });

        return $mapped->toArray();
    }

    public static function format_media_item(Media $record): array
    {
        return ['id' => $record['id'], 'urls' => ['full' => $record->getPath(), 'tn' => $record->getPath('tn')], 'order_column' => $record['order_column']];
    }
}
