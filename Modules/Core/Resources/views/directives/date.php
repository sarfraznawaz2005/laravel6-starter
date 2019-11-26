<?php
// When we want to show certain HTML on/after certain date.

/*
@dated('11/27/2019')
    This will only show on/after 11/27/2019
@enddated
*/

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;

Blade::if('dated', static function ($date) {
    return Carbon::now()->gte(Carbon::parse($date));
});
