<?php

namespace Modules\Crud\Http\Actions\Crud;

use File;
use Illuminate\Support\Facades\Artisan;
use Sarfraznawaz2005\Actions\Action;

class PublishCrud extends Action
{
    use CrudTrait;

    public function __invoke()
    {
        $output = '';

        File::cleanDirectory(base_path('public/modules'));

        File::cleanDirectory(base_path('database/migrations'));
        @file_put_contents(base_path('database/migrations') . '/.gitignore', '*');

        Artisan::call('vendor:publish', ['--all' => true]);

        $output .= Artisan::output();
        Artisan::call('module:publish-config', ['--force' => true]);
        $output .= Artisan::output();
        Artisan::call('module:publish-migration');
        $output .= Artisan::output();
        Artisan::call('module:publish-translation');
        $output .= Artisan::output();

        Artisan::call('module:publish');
        $output .= Artisan::output();

        //echo $output;exit;

        $this->deleteMigratedFiles();

        $this->optimize();

        flash('Modules Published Successfully!', 'success');
        return redirect()->back();
    }
}
