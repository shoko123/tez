<?php

namespace App\Models\Module\Specific\Fauna;

use Illuminate\Database\Eloquent\Model;

class FaunaOnp extends Model
{
    public $timestamps = false;

    protected $table = 'fauna_onps';

    public function item()
    {
        return $this->belongsToMany(Fauna::class, 'fauna-fauna_onps', 'onp_id', 'item_id');
    }
}
