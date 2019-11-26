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
        $output .= trim(Artisan::output());
        Artisan::call('module:publish-config', ['--force' => true]);
        $output .= trim(Artisan::output());
        Artisan::call('module:publish-migration');
        $output .= trim(Artisan::output());
        Artisan::call('module:publish-translation');
        $output .= trim(Artisan::output());
        Artisan::call('module:publish');
        $output .= trim(Artisan::output());

        $this->deleteMigratedFiles();

        $this->optimize();

        return flashBack('Modules Published Successfully!');
    }
}
