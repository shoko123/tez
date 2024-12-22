<?php

namespace App\Http\Controllers;

use App\Http\Requests\Module\IndexRequest;
use App\Http\Requests\Module\ItemByIdRequest;
use App\Http\Requests\Module\ModuleRequest;
use App\Http\Requests\Module\PageRequest;
use App\Http\Requests\Module\StoreRequest;

class DigModuleController extends BaseController
{
    /**
     * Get the module's init data (counts, trio, description_text)
     */
    public function init(ModuleRequest $r)
    {
        $v = $r->validated();
        $service = static::makeDigModuleService(ServiceEnum::Init, $v['module']);

        return response()->json($service->init(), 200);
    }

    /**
     * Filter the module's table and return an array of ids.
     */
    public function index(IndexRequest $r)
    {
        $v = $r->validated();
        $service = static::makeDigModuleService(ServiceEnum::Index, $v['module']);

        return response()->json($service->index($v['query'] ?? null), 200);
    }

    /**
     * Retrieve a sub-collection (page) of records of ids sent, formated according to the page's type ('Tabular' or 'Gallery')
     */
    public function page(PageRequest $r)
    {
        $v = $r->validated();
        $service = static::makeDigModuleService(ServiceEnum::Page, $r['module']);

        return response()->json($service->page($v['ids'], $v['view']), 200);
    }

    /**
     * Retrieve a single record and related data.
     */
    public function show(ItemByIdRequest $r)
    {
        $v = $r->validated();
        $service = static::makeDigModuleService(ServiceEnum::Show, $v['module']);

        return response()->json($service->show($v['id']), 200);
    }

    /**
     * Create/update a Module record.
     */
    public function store(StoreRequest $r)
    {
        $v = $r->validated();
        $mutateService = static::makeDigModuleService(ServiceEnum::Mutate, $v['module']);
        if ($r->isMethod('post')) {
            return response()->json($mutateService->create($v['data']), 201);
        }
        return response()->json($mutateService->update($v['data']), 200);
    }

    public function destroy(ItemByIdRequest $r)
    {
        $v = $r->validated();
        $mutateService = static::makeDigModuleService(ServiceEnum::Mutate, $r['module']);

        return response()->json($mutateService->destroy($v['module'], $v['id']), 200);
    }
}
