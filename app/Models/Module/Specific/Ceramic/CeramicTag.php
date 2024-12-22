<?php

namespace App\Models\Module\Specific\Ceramic;

use Illuminate\Database\Eloquent\Model;

class CeramicTag extends Model
{
    public $timestamps = false;

    protected $table = 'ceramic_tags';

    public function tag_group()
    {
        return $this->belongsTo(CeramicTagGroup::class, 'tag_group_id');
    }

    public function item()
    {
        return $this->belongsToMany(Ceramic::class, 'ceramic-ceramic_tags', 'tag_id', 'item_id');
    }
}
