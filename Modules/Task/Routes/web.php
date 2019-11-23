<?php

use Modules\Task\Http\Actions\Task\CompleteTask;
use Modules\Task\Http\Actions\Task\DestroyTask;
use Modules\Task\Http\Actions\Task\UpdateTask;
use Modules\Task\Http\Actions\Task\EditTask;
use Modules\Task\Http\Actions\Task\StoreTask;
use Modules\Task\Http\Actions\Task\IndexTask;

Route::group(['middleware' => ['auth', 'verified']], static function () {

    Route::group(['namespace' => '\\'], static function () {
        Route::get('tasks', IndexTask::class)->name('tasks.index');
        Route::post('tasks', StoreTask::class)->name('tasks.store');
        Route::get('tasks/{task}/edit', EditTask::class)->name('tasks.edit');
        Route::put('tasks/{task}', UpdateTask::class)->name('tasks.update');
        Route::delete('tasks/{task}', DestroyTask::class)->name('tasks.destroy');
        Route::get('tasks/{task}/complete', CompleteTask::class)->name('tasks.complete');
    });
});





