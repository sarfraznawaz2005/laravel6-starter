<?php

use Modules\Main\Http\Actions\Home\IndexHome;

Route::group(['middleware' => 'XSSProtection'], static function () {
    Route::get('{url}', '\\' .IndexHome::class)->where(['url' => '/|home|'])->name('home');
});



