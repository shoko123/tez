<?php

namespace App\Models\Module\Specific\Glass;

use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Models\Module\DigModuleModel;
use App\Models\Module\Specific\Locus\Locus;
use App\Models\Tag\Tag;

class Glass extends DigModuleModel
{
    protected $table = 'glass';
    protected $moduleTagTable = 'glass_tags';

    static public function restrictedFieldValues(): array
    {
        return ['code' => ['AR']];
    }

    static public function dateFields(): array
    {
        return ['date_retrieved'];
    }

    public function locus()
    {
        return $this->belongsTo(Locus::class);
    }

    public function module_tags()
    {
        return $this->belongsToMany(GlassTag::class, 'glass-glass_tags', 'item_id', 'tag_id');
    }

    public function global_tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function primaryClassification()
    {
        return $this->belongsTo(GlassPrimaryClassification::class, 'glass_primary_classification_id');
    }

    // protected function casts(): array
    // {
    //     return [
    //         'whole' => 'boolean',
    //     ];
    // }

    protected function derivedId(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['locus_id'] . $attributes['code'] . str_pad($attributes['basket_no'], 2, '0', STR_PAD_LEFT) . str_pad($attributes['artifact_no'], 2, '0', STR_PAD_LEFT)
        );
    }

    protected function short(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['description'] ?? '[No description]'
        );
    }


    static public function discreteFilterOptions(): array
    {
        return [
            'Season' => [
                'field' => 'locus_id',
                'start' => 1,
                'length' => 1
            ],
            'Area' => [
                'field' => 'locus_id',
                'start' => 2,
                'length' => 1
            ],
            'Registration Code' => 'code',
            'Primary Classification' => 'glass_primary_classification_id',
            'Code' => 'code',
            'Locus Id' => 'locus_id' // This one is required by create to avoid duplicate ids.
            // It is not a part of the tag/filter system.
        ];
    }


    static public function orderByOptions(): array
    {
        return [
            'Season' => [
                'field' => 'locus_id',
                'start' => 1,
                'length' => 1
            ],
            'Area' => [
                'field' => 'locus_id',
                'start' => 2,
                'length' => 1
            ],
            'Locus No.' =>            [
                'field' => 'locus_id',
                'start' => 3,
                'length' => 3
            ],
            'Basket No.' => 'basket_no',
            'Artifact No.' => 'artifact_no',

        ];
    }
}
