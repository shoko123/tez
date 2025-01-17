<?php

namespace App\Models\Module\Specific\Fauna;

use Illuminate\Database\Eloquent\Model;

class FaunaTaxa extends Model
{
    public $timestamps = false;

    protected $table = 'fauna_primary_taxa';
}
