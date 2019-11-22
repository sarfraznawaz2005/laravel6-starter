<?php

use Modules\Crud\Http\Actions\Crud\CreateFileCrud;
use Modules\Crud\Http\Actions\Crud\ToggleStatusCrud;
use Modules\Crud\Http\Actions\Crud\MigrateCrud;
use Modules\Crud\Http\Actions\Crud\PublishCrud;
use Modules\Crud\Http\Actions\Crud\StoreCrud;
use Modules\Crud\Http\Actions\Crud\DestroyCrud;
use Modules\Crud\Http\Actions\Crud\IndexCrud;
use Modules\Crud\Http\Actions\Crud\CompileCrud;

Route::group(['middleware' => 'XSSProtection'], static function () {

    Route::group([
        'middleware' => [
            'auth.very_basic',
            'throttle:50'
        ]
    ], static function () {
        Route::get('crud__', '\\' . IndexCrud::class)->name('crud.index');
    });

    Route::group([
        'prefix' => 'crud',
        'middleware' => [
            'auth.very_basic',
            'throttle:50'
        ]
    ], static function () {
        Route::group(['namespace' => '\\'], static function () {
            Route::post('cruds', StoreCrud::class)->name('crud.store');
            Route::get('cruds/publish', PublishCrud::class)->name('crud.publish');
            Route::get('cruds/migrate', MigrateCrud::class)->name('crud.migrate');
            Route::get('cruds/togglestatus/{name}', ToggleStatusCrud::class)->name('crud.toggle_status');
            Route::post('cruds/createfile', CreateFileCrud::class)->name('crud.createfile');
            Route::get('cruds/compile', CompileCrud::class)->name('crud.compile');
            Route::delete('cruds/{crud}', DestroyCrud::class)->name('crud.destroy');
        });
    });

});



