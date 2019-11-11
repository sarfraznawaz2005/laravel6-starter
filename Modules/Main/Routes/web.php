<?php

Route::group(['middleware' => 'XSSProtection'], function () {

    Route::get('/', 'MainController')->name('home');
    Route::get('/home', 'MainController')->name('home');

});



