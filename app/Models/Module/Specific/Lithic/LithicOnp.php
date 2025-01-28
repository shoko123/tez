<?php

namespace App\Models\Module\Specific\Lithic;

use Illuminate\Database\Eloquent\Model;

class LithicOnp extends Model
{
    public $timestamps = false;

    protected $table = 'lithic_onps';

    public function item()
    {
        return $this->belongsToMany(Lithic::class, 'lithic-lithic_onps', 'onp_id', 'item_id');
    }
}
