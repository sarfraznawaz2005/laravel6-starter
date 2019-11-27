<?php
/*
@env('production')
    Show on Production only
@elseenv('testing')
    Show on Testing only
@endenv

OR

@env(['production', 'testing'])
    Show on Production/Testing only
@endenv
*/

use Illuminate\Support\Facades\Blade;

Blade::if('env', static function ($environment) {
    return app()->environment($environment);
});
