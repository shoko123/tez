<?php

namespace App\Models\Module\Specific\Metal;

use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Models\Module\DigModuleModel;
use App\Models\Module\Specific\Locus\Locus;
use App\Models\Tag\Tag;

class Metal extends DigModuleModel
{
    protected $table = 'metals';
    protected $moduleTagTable = 'metal_tags';

    static public function enumFields(): array
    {
        return [];
    }

    static public function dateFields(): array
    {
        return ['date_retrieved'];
    }

    static public function restrictedFieldValues(): array
    {
        return ['code' => ['AR']];
    }

    public function locus()
    {
        return $this->belongsTo(Locus::class);
    }

    public function module_tags()
    {
        return $this->belongsToMany(MetalTag::class, 'metal-metal_tags', 'item_id', 'tag_id');
    }

    public function global_tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function primaryClassification()
    {
        return $this->belongsTo(MetalPrimaryClassification::class, 'metal_primary_classification_id');
    }

    public function material()
    {
        return $this->belongsTo(MetalMaterial::class, 'material_id');
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
            'Material' => 'material_id',
            'Primary Classification' => 'metal_primary_classification_id',
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
