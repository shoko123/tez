<?php

namespace App\Http\Controllers;

use App\Http\Requests\Carousel\CarouselRequest;
use App\Services\App\MediaService;

class CarouselController extends BaseController
{
    /**
     * Retrieve data needed to show a carousel item. Source may be 'main', or 'media' TODO 'related'.
     */
    public function show(CarouselRequest $r)
    {
        $v = $r->validated();
        switch ($v['source']) {
            case 'media':
                return response()->json(MediaService::show_carousel($v['media_id']), 200);

            case 'main':
                $rs = static::makeDigModuleService(ServiceEnum::Show, $v['module']);

                return response()->json($rs->show_carousel($v['module'], $v['module_id']), 200);

            case 'related':
            default:
                abort(422, 'unsupported "source": ' . $v['source']);
        }
    }
}
