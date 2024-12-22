<?php

namespace App\Models\Module\Specific\Season;

use App\Models\Module\DigModuleModel;
use App\Models\Tag\Tag;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Season extends DigModuleModel
{
    protected $table = 'seasons';

    static public function restrictedFieldValues(): array
    {
        return ['id' => ['3', '4', '5', '6', '7', '8']];
    }

    static public function dateFields(): array
    {
        return [];
    }

    protected function derivedId(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['id']
        );
    }

    protected function short(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['description']
        );
    }

    static public function discreteFilterOptions(): array
    {
        return [];
    }
    static public function orderByOptions(): array
    {
        return [];
    }
}
