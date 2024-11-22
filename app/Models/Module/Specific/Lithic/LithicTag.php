<?php

namespace App\Models\Module\Specific\Lithic;

use Illuminate\Database\Eloquent\Model;

class LithicTag extends Model
{
    public $timestamps = false;

    protected $table = 'lithic_tags';

    public function tag_group()
    {
        return $this->belongsTo(LithicTagGroup::class, 'tag_group_id');
    }

    public function item()
    {
        return $this->belongsToMany(Lithic::class, 'lithic-lithic_tags', 'tag_id', 'item_id');
    }
}
