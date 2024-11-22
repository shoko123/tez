<?php

namespace App\Models\Module\Specific\Glass;

use Illuminate\Database\Eloquent\Model;

class GlassTagGroup extends Model
{
    public $timestamps = false;

    protected $table = 'glass_tag_groups';

    public function tags()
    {
        return $this->hasMany(GlassTag::class, 'tag_group_id', 'id');
    }
}
