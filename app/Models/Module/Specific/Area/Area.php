<?php

namespace App\Models\Module\Specific\Area;

use App\Models\Module\DigModuleModel;
use App\Models\Tag\Tag;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Area extends DigModuleModel
{
    protected $table = 'areas';

    static public function enumFields(): array
    {
        return [];
    }

    static public function dateFields(): array
    {
        return [];
    }

    static public function restrictedFieldValues(): array
    {
        return ['id' => ['K', 'L', 'M', 'N', 'P', 'Q', 'S']];
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
