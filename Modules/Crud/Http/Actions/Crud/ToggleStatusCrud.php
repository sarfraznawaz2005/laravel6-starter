<?php

namespace Modules\Crud\Http\Actions\Crud;

use Nwidart\Modules\Facades\Module;
use Sarfraznawaz2005\Actions\Action;

class ToggleStatusCrud extends Action
{
    public function __invoke($moduleName)
    {
        if ($moduleName !== 'Core' && $moduleName !== 'Crud') {
            if (Module::has($moduleName)) {
                $module = Module::find($moduleName);

                if ($module) {
                    $status = 'Enabled';
                    $isEnabled = $module->isStatus(1);

                    if ($isEnabled) {
                        $status = 'Disabled';
                        $module->disable();
                    } else {
                        $module->enable();
                    }

                    return flashBack("Module $status Successfully!");
                }
            }
        }

        return back();
    }
}
