<?php

namespace Modules\Crud\Http\Actions\Crud;

use File;
use Illuminate\Support\Facades\Artisan;
use Sarfraznawaz2005\Actions\Action;

class MigrateCrud extends Action
{
    use CrudTrait;

    public function __invoke()
    {
        $output = '';

        File::cleanDirectory(base_path('database/migrations'));
        @file_put_contents(base_path('database/migrations') . '/.gitignore', '*');
        Artisan::call('module:publish-migration');
        sleep(3);

        $this->deleteMigratedFiles();

        Artisan::call('migrate', ['--force' => true]);
        $output .= Artisan::output();

        flash($output ? nl2br($output) : 'Nothing to migrate.', 'success');

        return back();
    }
}
