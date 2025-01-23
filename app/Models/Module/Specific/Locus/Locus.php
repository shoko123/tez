<?php

namespace App\Models\Module\Specific\Locus;

use Illuminate\Database\Eloquent\Casts\Attribute;

use App\Services\App\Utils\GetService;
use App\Models\Module\DigModuleModel;
use App\Models\Tag\Tag;

class Locus extends DigModuleModel
{
    protected $table = 'loci';
    protected $moduleTagTable = 'locus_tags';

    static public function enumFields(): array
    {
        return [];
    }

    static public function dateFields(): array
    {
        return ['date_opened', 'date_closed'];
    }

    public function area()
    {
        return $this->belongsTo(GetService::getModel('Area'));
    }

    public function season()
    {
        return $this->belongsTo(GetService::getModel('Season'));
    }

    public function ceramics()
    {
        return $this->hasMany(GetService::getModel('Ceramic'));
    }

    public function fauna()
    {
        return $this->hasMany(GetService::getModel('Fauna'), 'locus_id', 'id');
    }

    public function glass()
    {
        return $this->hasMany(GetService::getModel('Glass'));
    }

    public function lithics()
    {
        return $this->hasMany(GetService::getModel('Lithic'), 'locus_id', 'id');
    }

    public function metals()
    {
        return $this->hasMany(GetService::getModel('Metal'));
    }

    public function stones()
    {
        return $this->hasMany(GetService::getModel('Stone'), 'locus_id', 'id');
    }

    public function module_tags()
    {
        return $this->belongsToMany(LocusTag::class, 'locus-locus_tags', 'item_id', 'tag_id');
    }

    public function global_tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    protected function derivedId(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['season_id'] .  $attributes['area_id'] . str_pad($attributes['locus_no'], 3, '0', STR_PAD_LEFT)
        );
    }

    protected function short(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['description']
        );
    }

    static public function discreteFilterOptions(): array
    {
        return [
            'Area' => 'area_id',
            'Season' => 'season_id',
        ];
    }

    static public function orderByOptions(): array
    {
        return [
            'Area' => 'area_id',
            'Season' => 'season_id',
            'Locus No.' => 'locus_no'
        ];
    }
}
