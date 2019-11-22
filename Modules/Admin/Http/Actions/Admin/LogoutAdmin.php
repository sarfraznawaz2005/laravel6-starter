<?php

namespace Modules\Admin\Http\Actions\Admin;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Sarfraznawaz2005\Actions\Action;

class LogoutAdmin extends Action
{
    use AuthenticatesUsers;

    protected $redirectTo = 'admin/panel';

    public function __invoke()
    {
        $this->guard()->logout();

        request()->session()->flush();
        request()->session()->regenerate();
        request()->session()->invalidate();

        noty('You are logged out.', 'warning');

        return redirect('admin');
    }
}
