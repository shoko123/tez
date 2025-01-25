<?php

namespace App\Models\Module\Specific\Area;

use App\Models\Module\DigModuleModel;
use App\Models\Tag\Tag;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Area extends DigModuleModel
{
    protected $table = 'areas';

    public static function restrictedValues(): array
    {
        return ['id' => [
            'vals' => ['K', 'L', 'M', 'N', 'P', 'Q', 'S'],
        ]];
    }

    public static function enumFields(): array
    {
        return [];
    }

    public static function dateFields(): array
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

    public static function discreteFilterOptions(): array
    {
        return [];
    }

    public static function orderByOptions(): array
    {
        return [];
    }
}
