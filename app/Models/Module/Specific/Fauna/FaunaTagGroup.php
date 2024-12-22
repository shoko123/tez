<?php

namespace App\Models\Module\Specific\Fauna;

use Illuminate\Database\Eloquent\Model;

class FaunaTagGroup extends Model
{
    public $timestamps = false;

    protected $table = 'fauna_tag_groups';

    public function tags()
    {
        return $this->hasMany(FaunaTag::class, 'tag_group_id', 'id');
    }
}
