<?php

namespace App\Models\Module\Specific\Metal;

use Illuminate\Database\Eloquent\Model;

class MetalTag extends Model
{
    public $timestamps = false;

    protected $table = 'metal_tags';

    public function tag_group()
    {
        return $this->belongsTo(MetalTagGroup::class, 'tag_group_id');
    }

    public function item()
    {
        return $this->belongsToMany(Metal::class, 'metal-metal_tags', 'tag_id', 'item_id');
    }
}
