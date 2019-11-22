<?php

namespace Modules\Admin\Http\Actions\Admin;

use Sarfraznawaz2005\Actions\Action;

class PanelAdmin extends Action
{
    public function __invoke()
    {
        title('Dashboard');

        return view('admin::pages.panel.index');
    }
}
