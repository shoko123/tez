<?php

namespace App\Models\Module\Specific\Lithic;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Module\DigModuleModel;
use App\Models\Module\Specific\Locus\Locus;
use App\Models\Tag\Tag;

class Lithic extends DigModuleModel
{
    protected $table = 'lithics';
    protected $moduleTagTable = 'lithic_tags';
    protected $onpTable = 'lithic_onps';

    public static function restrictedValues(): array
    {
        return [];
    }

    public static function enumFields(): array
    {
        return [];
    }

    public static function dateFields(): array
    {
        return ['date_retrieved'];
    }

    public function locus()
    {
        return $this->belongsTo(Locus::class);
    }

    public function module_tags()
    {
        return $this->belongsToMany(LithicTag::class, 'lithic-lithic_tags', 'item_id', 'tag_id');
    }

    public function global_tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function onps()
    {
        return $this->belongsToMany(LithicOnp::class, 'lithic-lithic_onps', 'item_id', 'onp_id')->withPivot('value');
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
            get: fn(mixed $value, array $attributes) => $attributes['field_description'] ?? '[No field description]'
        );
    }

    public static function discreteFilterOptions(): array
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
            'Primary Classification' => 'lithic_primary_classification_id',
            'Code' => 'code',
            'Locus Id' => 'locus_id' // This one is required by create to avoid duplicate ids.
            // It is not a part of the tag/filter system.
        ];
    }

    public static function orderByOptions(): array
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
            'Registration Code' => 'code',
            'Basket No.' => 'basket_no',
            'Artifact No.' => 'artifact_no',
        ];
    }
}
