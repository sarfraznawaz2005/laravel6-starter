<?php

use Modules\Admin\Http\Actions\Admin\IndexAdmin;
use Modules\Admin\Http\Actions\Admin\LoginAdmin;
use Modules\Admin\Http\Actions\Admin\LogoutAdmin;
use Modules\Admin\Http\Actions\Admin\PanelAdmin;
use Modules\Admin\Http\Actions\User\IndexUser;

Route::prefix('admin')->group(static function () {

    #===========================================================#
    ### PUBLIC ROUTES START ###
    #===========================================================#

    Route::get('/', '\\' . IndexAdmin::class)->name('admin.login');
    Route::post('login', '\\' . LoginAdmin::class);

    ### PUBLIC ROUTES END ###
    #===========================================================#

    #===========================================================#
    ### AUTHENTICATED ROUTES START ###
    #===========================================================#
    Route::group(['middleware' => ['admin', 'verified']], static function () {
        Route::get('logout', '\\' . LogoutAdmin::class)->name('admin.logout');
        Route::get('panel', '\\' . PanelAdmin::class)->name('admin.panel');

        Route::get('users', '\\' . IndexUser::class)->name('admin.users.index');
    });
    #===========================================================#
    ### AUTHENTICATED ROUTES END ###
    #===========================================================#

});




