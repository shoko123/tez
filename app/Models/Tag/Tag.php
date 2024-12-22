<?php

namespace App\Models\Tag;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;

    protected $table = 'tags';

    public function tag_group()
    {
        return $this->belongsTo(TagGroup::class, 'tag_group_id');
    }

    public function loci()
    {
        return $this->morphedByMany('Locus', 'taggable');
    }

    public function ceramics()
    {
        return $this->morphedByMany('Ceramic', 'taggable');
    }

    public function fauna()
    {
        return $this->morphedByMany('Fauna', 'taggable');
    }

    public function glass()
    {
        return $this->morphedByMany('Glass', 'taggable');
    }

    public function lithics()
    {
        return $this->morphedByMany('Lithic', 'taggable');
    }

    public function metals()
    {
        return $this->morphedByMany('Metal', 'taggable');
    }

    public function stones()
    {
        return $this->morphedByMany('Stone', 'taggable');
    }
}
