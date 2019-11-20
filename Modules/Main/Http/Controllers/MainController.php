<?php

namespace Modules\Main\Http\Controllers;

use Modules\Core\Http\Controllers\Controller;

class MainController extends Controller
{
    public function __invoke()
    {
        title('Welcome');

        return view('main::pages.home.index');
    }
}
