<?php

namespace App\Models\Module\Specific\Fauna;

use Illuminate\Database\Eloquent\Model;

class FaunaTag extends Model
{
    public $timestamps = false;

    protected $table = 'fauna_tags';

    public function tag_group()
    {
        return $this->belongsTo(FaunaTagGroup::class, 'tag_group_id');
    }

    public function item()
    {
        return $this->belongsToMany(Fauna::class, 'fauna-fauna_tags', 'tag_id', 'item_id');
    }
}
