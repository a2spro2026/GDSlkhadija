<?php

namespace App\Http\Controllers;

class ModuleController extends Controller
{
    public function show(string $module)
    {
        $pages = config('modules');

        if (! isset($pages[$module])) {
            abort(404);
        }

        $page = $pages[$module];

        return view('modules.show', [
            'module' => $module,
            'page' => $page,
        ]);
    }
}
