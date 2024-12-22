<?php

namespace App\Models\Module\Specific\Stone;

use Illuminate\Database\Eloquent\Model;

class StoneTag extends Model
{
    public $timestamps = false;

    protected $table = 'stone_tags';

    public function tag_group()
    {
        return $this->belongsTo(StoneTagGroup::class, 'tag_group_id');
    }

    public function item()
    {
        return $this->belongsToMany(Stone::class, 'stone-stone_tags', 'tag_id', 'item_id');
    }
}
