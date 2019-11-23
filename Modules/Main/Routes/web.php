<?php

use Modules\Main\Http\Actions\Home\IndexHome;

Route::get('{url}', '\\' .IndexHome::class)->where(['url' => '/|home|'])->name('home');



