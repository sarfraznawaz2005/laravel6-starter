<?php

namespace Modules\Crud\Http\Actions\Crud;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Sarfraznawaz2005\Actions\Action;

class IndexCrud extends Action
{
    public function __invoke()
    {
        title('Module Manager');

        $migrationsPending = $this->areMigrationsPending();

        return view('crud::pages.index', compact('migrationsPending'));
    }

    protected function areMigrationsPending()
    {
        Artisan::call('migrate:status');
        $output = trim(Artisan::output());

        if (Str::contains(trim($output), 'No migrations')) {
            return false;
        }

        $output = collect(explode("\n", $output));
        $output = $output->reject(static function ($item) {
            return !Str::contains($item, '| N');
        });

        $count = $output->count() !== 0;

        if ($count) {
            return $output;
        }

        return false;
    }
}
