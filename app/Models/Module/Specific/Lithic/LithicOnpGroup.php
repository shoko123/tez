<?php

namespace App\Models\Module\Specific\Lithic;

use Illuminate\Database\Eloquent\Model;

class LithicOnpGroup extends Model
{
    public $timestamps = false;

    protected $table = 'lithic_onp_groups';

    public function onps()
    {
        return $this->hasMany(LithicOnp::class, 'onp_group_id', 'id');
    }
}
