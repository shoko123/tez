<?php

namespace App\Models\Module\Specific\Lithic;

use Illuminate\Database\Eloquent\Model;

class LithicTagGroup extends Model
{
    public $timestamps = false;

    protected $table = 'lithic_tag_groups';

    public function tags()
    {
        return $this->hasMany(LithicTag::class, 'tag_group_id', 'id');
    }
}
