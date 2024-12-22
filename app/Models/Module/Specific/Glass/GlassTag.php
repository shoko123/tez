<?php

namespace App\Models\Module\Specific\Glass;

use Illuminate\Database\Eloquent\Model;

class GlassTag extends Model
{
    public $timestamps = false;

    protected $table = 'glass_tags';

    public function tag_group()
    {
        return $this->belongsTo(GlassTagGroup::class, 'tag_group_id');
    }

    public function item()
    {
        return $this->belongsToMany(Glass::class, 'glass-glass_tags', 'tag_id', 'item_id');
    }
}
