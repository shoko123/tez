<?php

namespace App\Models\Module\Specific\Lithic;

use Illuminate\Database\Eloquent\Model;

class LithicOnp extends Model
{
    public $timestamps = false;

    protected $table = 'lithic_onps';

    public function onp_group()
    {
        return $this->belongsTo(LithicOnpGroup::class, 'onp_group_id');
    }

    public function item()
    {
        return $this->belongsToMany(Lithic::class, 'lithic-lithic_onps', 'num_id', 'item_id');
    }
}
