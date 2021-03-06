<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Sarfraz Ahmed (sarfraznawaz2005@gmail.com)">

    <link rel="shortcut icon" href="/favicon.ico">

    <title>{{appName()}}</title>

    <link rel="stylesheet" href="{{mix('/css/app.css')}}">
    <link rel="stylesheet" href="/modules/admin/css/main.css">
    <link rel="stylesheet" href="/modules/admin/css/custom.css">

</head>

<body class="animated fadeIn">

<section class="material-half-bg">
    <div class="cover"></div>
</section>
<section class="login-content">

    <div class="logo">
        <h1>{{appName()}}</h1>
    </div>

    @include('core::shared.flash')
    @include('core::shared.errors')

    <div class="login-box">

        {!! Former::open()->action(url('/admin/login'))->method('post')->class('validate login-form') !!}
        <h3 class="login-head">
            <i class="fa fa-lg fa-fw fa-user"></i>SIGN IN
        </h3>

        {!!
            Former::email('email', 'E-Mail Address')
            ->required()
            ->autocomplete('off')
        !!}

        {!!
            Former::password('password', 'Password')
            ->required()
            ->autocomplete('off')
        !!}

        <div class="form-group">
            <div class="utility">
                @if(config('user.remember_me_checkbox', true))
                    <div class="animated-checkbox">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="label-text">Stay Signed in</span>
                        </label>
                    </div>
                @endif
                <p class="semibold-text mb-2">
                    <a href="{{ route('password.request') }}" data-toggle="flip">Forgot Password ?</a>
                </p>
            </div>
        </div>

        <div class="form-group btn-container">
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fa fa-sign-in fa-lg fa-fw"></i> SIGN IN
            </button>
        </div>

        {!! Former::close() !!}
    </div>
</section>

</body>
</html>
