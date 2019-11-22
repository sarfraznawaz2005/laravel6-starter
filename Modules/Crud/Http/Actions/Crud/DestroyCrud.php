<?php

namespace Modules\Crud\Http\Actions\Crud;

use Nwidart\Modules\Facades\Module;
use Sarfraznawaz2005\Actions\Action;

class DestroyCrud extends Action
{
    public function __invoke($moduleName)
    {
        if (Module::has($moduleName)) {
            if (!in_array($moduleName, Module::getSystemModules(), true)) {
                $module = Module::find($moduleName);

                if ($module && $module->delete()) {
                    flash('Deleted Successfully!', 'success');
                }
            }
        }

        return back();
    }
}
