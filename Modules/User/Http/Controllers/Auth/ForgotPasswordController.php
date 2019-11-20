<?php

namespace Modules\User\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Routing\Controller;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function showLinkRequestForm()
    {
        title('Reset Your Password');

        return view('user::auth.passwords.email');
    }

    public function __construct()
    {
        $this->middleware('guest');
    }
}
