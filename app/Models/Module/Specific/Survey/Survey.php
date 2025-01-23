<?php

namespace App\Models\Module\Specific\Survey;

use App\Models\Module\DigModuleModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Survey extends DigModuleModel
{
    protected $table = 'survey';

    static public function enumFields(): array
    {
        return [];
    }

    static public function dateFields(): array
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

    static public function discreteFilterOptions(): array
    {
        return ['Area' => 'area_id'];
    }

    static public function orderByOptions(): array
    {
        return ['Area' => 'area_id', 'Feature No.' => 'feature_no'];
    }
}
