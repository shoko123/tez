<?php

namespace App\Models\Module\Specific\Fauna;

use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Models\Module\DigModuleModel;
use App\Models\Module\Specific\Locus\Locus;
use App\Models\Tag\Tag;

class Fauna extends DigModuleModel
{
    protected $table = 'fauna';
    protected $moduleTagTable = 'fauna_tags';

    public static function restrictedValues(): array
    {
        return [];
    }

    public static function enumFields(): array
    {
        return ['code', 'symmetry', 'weathering'];
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
        return $this->belongsToMany(FaunaTag::class, 'fauna-fauna_tags', 'item_id', 'tag_id');
    }

    public function global_tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function primaryTaxon()
    {
        return $this->belongsTo(FaunaTaxa::class, 'primary_taxon_id');
    }

    // protected function casts(): array
    // {
    //     return [
    //         'has_butchery_evidence' => 'boolean',
    //         'has_burning_evidence' => 'boolean',
    //         'has_other_bsm_evidence' => 'boolean',

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
            get: fn(mixed $value, array $attributes) => $attributes['taxa'] ?? '[No description]'
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
            'Taxa' => 'primary_taxon_id',
            'Code' => 'code',
            'Primary Taxa' => 'primary_taxon_id',
            'Fauna Scope' => 'scope_id',
            'Material' => 'material_id',
            'Symmetry' => 'symmetry',
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
            'Basket No.' => 'basket_no',
            'Artifact No.' => 'artifact_no',
        ];
    }
}
