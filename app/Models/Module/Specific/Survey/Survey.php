<?php

namespace App\Models\Module\Specific\Survey;

use App\Models\Module\DigModuleModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Survey extends DigModuleModel
{
    protected $table = 'survey';

    public static function restrictedValues(): array
    {
        return [];
    }

    public static function enumFields(): array
    {
        return [];
    }

    public static function dateFields(): array
    {
        return ['surveyed_date'];
    }

    protected function derivedId(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['area_id'] . (string)$attributes['feature_no']
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
        return ['Area' => 'area_id'];
    }

    public static function orderByOptions(): array
    {
        return ['Area' => 'area_id', 'Feature No.' => 'feature_no'];
    }
}
