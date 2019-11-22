<?php

namespace Modules\Crud\Http\Actions\Crud;

use Illuminate\Support\Facades\Artisan;
use Sarfraznawaz2005\Actions\Action;

class CompileCrud extends Action
{
    public function __invoke()
    {
        set_time_limit(0);

        $type = 'dev';

        if (app()->environment(['prod', 'production', 'live'])) {
            $type = 'prod';
        }

        $output = shell_exec("npm run $type 2>&1");
        Artisan::call('module:publish');

        if (false !== stripos($output, 'Compiled successfully')) {
            flash("npm run $type process finished successfully", 'success');
        } else {
            flash("npm run $type process failed", 'danger');
        }

        return redirect()->back();
    }
}
