<?php

namespace Modules\Laralite\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Module;

class ModuleController extends Controller
{
    public function get()
    {
        $rawModules = Module::all();
        $modules = [];

        foreach ($rawModules as $module) {
            $modules[] = [
                'label' => $module->getName(),
            ];
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Fetched list of modules',
            'data' => [
                'modules' => $modules,
            ],
        ]);
    }
}
