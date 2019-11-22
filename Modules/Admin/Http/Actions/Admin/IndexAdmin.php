<?php

namespace Modules\Admin\Http\Actions\Admin;

use Sarfraznawaz2005\Actions\Action;

class IndexAdmin extends Action
{
    public function __invoke()
    {
        if (auth()->check() && user()->isSuperAdmin()) {
            return redirect(route('admin.panel'));
        }

        return view('admin::pages.login.index');
    }
}
