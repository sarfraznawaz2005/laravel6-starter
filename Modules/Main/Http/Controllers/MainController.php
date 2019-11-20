<?php

namespace Modules\Main\Http\Controllers;

class MainController
{
    public function __invoke()
    {
        title('Welcome');

        return view('main::pages.home.index');
    }
}
