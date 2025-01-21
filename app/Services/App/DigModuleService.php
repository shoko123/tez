<?php

namespace App\Services\App;

use App\Services\App\Utils\GetService;
use App\Models\Module\DigModuleModel;

class DigModuleService
{
    protected DigModuleModel $model;
    protected static $module;
    protected static $tableName;

    public function __construct(string $module)
    {
        $this->model = GetService::getModel($module, true);
        static::$module = $module;
        static::$tableName = $this->model->tableName();
    }

    function uses_tags()
    {
        return property_exists($this->model, 'moduleTagTable');
    }

    function uses_onps()
    {
        return property_exists($this->model, 'onpTable');
    }
}
