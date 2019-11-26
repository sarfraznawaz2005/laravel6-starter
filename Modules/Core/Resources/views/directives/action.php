<?php
// Sometimes we want to render HTML only when controller action matches something.

/*
@action('index')
    <p>This is rendered only if I am in the controller action INDEX.</p>
@endaction
*/

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;

Blade::if('action', static function ($action) {
    if (Route::getCurrentRoute()->getActionMethod() === $action) {
        return $action;
    };
});
