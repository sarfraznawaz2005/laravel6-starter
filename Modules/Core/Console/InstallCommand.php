<?php

namespace Modules\Core\Console;

use Illuminate\Console\Command;
use RecursiveIteratorIterator;

class InstallCommand extends Command
{
    protected $signature = 'app:install';
    protected $description = 'Installs Modular Structure';

    public function handle()
    {
        $this->comment('Publishing Vendors...');
        $this->callSilent('vendor:publish', ['--all' => true]);

        $this->comment('Publishing Modules...');
        $this->callSilent('module:publish');

        $this->comment('Publishing Module Migrations...');
        $this->callSilent('module:publish-migration');

        $this->comment('Creating Storage Symbolic Link...');
        $this->callSilent('storage:link');

        $this->comment('Running Migrations...');
        $this->callSilent('migrate');

        $this->comment('Done!');
    }
}
