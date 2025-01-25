<?php

namespace App\Models\Module\Specific\Season;

use App\Models\Module\DigModuleModel;
use App\Models\Tag\Tag;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Season extends DigModuleModel
{
    protected $table = 'seasons';

    public static function restrictedValues(): array
    {
        return ['id' => [
            'vals' => ['3', '4', '5', '6', '7', '8'],
            'manipulator' => function ($val) {
                return (string) ($val + 2010);
            }
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
