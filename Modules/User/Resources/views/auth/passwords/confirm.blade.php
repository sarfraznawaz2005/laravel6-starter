@extends('main::layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">

                @card(['type' => 'white', 'header_type' => 'light', 'classes' => 'mb3'])
                @slot('header')
                    <strong><i class="fa fa-lock"></i> {{ __('Confirm Password') }}</strong>
                @endslot

                {{ __('Please confirm your password before continuing.') }}

                {!! Former::open()->action(route('password.confirm'))->method('post')->class('validate') !!}

                {!!
                      Former::password('password', 'Password')
                      ->required()
                      ->label('')
                      ->placeholder('Password')
                      ->autocomplete('off')
                  !!}

                {!!
                Former::actions(Former::primary_button('<span class="fa fa-paper-plane"></span> Confirm Password')
                ->type('submit')
                ->class('btn btn-block btn-success btn-raised'))
                !!}

                {!! Former::close() !!}

                @if (Route::has('password.request'))
                    <div class="text-center">
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                @endif

                @endcard

            </div>
        </div>
    </div>
@endsection
