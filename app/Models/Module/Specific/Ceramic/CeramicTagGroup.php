<?php

namespace App\Models\Module\Specific\Ceramic;

use Illuminate\Database\Eloquent\Model;

class CeramicTagGroup extends Model
{
    public $timestamps = false;

    protected $table = 'ceramic_tag_groups';

    public function tags()
    {
        return $this->hasMany(CeramicTag::class, 'tag_group_id', 'id');
    }
}
