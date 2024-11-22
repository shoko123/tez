<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

use App\Services\App\Utils\GetService;



class BaseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected static function makeDigModuleService(ServiceEnum $service, string $module)
    {
        if (! in_array($module, [
            'Area',
            'Season',
            'Survey',
            'Locus',
            'Ceramic',
            'Fauna',
            'Glass',
            'Lithic',
            'Metal',
            'Stone',
        ])) {
            abort(422, '*** Illegal module field value: "' . $module . '"');
        }


        switch ($service) {
            case ServiceEnum::Init:
                return GetService::getService('Init', $module);
                break;

            case ServiceEnum::Mutate:
                return GetService::getService('Mutate', $module);

            case ServiceEnum::Index:
                return GetService::getService('Index', $module);
                break;

            case ServiceEnum::Page:
                return GetService::getService('Page', $module);
                break;

            case ServiceEnum::Show:
                return GetService::getService('Show', $module);

            default:
                abort(422, '*** Illegal service value');
        }
    }
}
