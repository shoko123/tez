<?php

namespace App\Models\Module\Specific\Metal;

use Illuminate\Database\Eloquent\Model;

class MetalTagGroup extends Model
{
    public $timestamps = false;

    protected $table = 'metal_tag_groups';

    public function tags()
    {
        return $this->hasMany(MetalTag::class, 'tag_group_id', 'id');
    }
}
