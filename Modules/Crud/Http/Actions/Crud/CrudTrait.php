<?php

namespace Modules\Crud\Http\Actions\Crud;

use DB;
use File;
use Illuminate\Support\Facades\Artisan;

trait CrudTrait
{
    protected $nonModuleCommands = [
        'make:action',
        'make:widget'
    ];

    /**
     * @param $commandName
     * @param string $commandArgument
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function runCommand($commandName, $commandArgument = '', $message = ''): \Illuminate\Http\RedirectResponse
    {
        $moduleName = request()->get('module');
        $name = request()->get('name');

        if ($commandArgument) {
            $command = $this->getArtisan() . $commandName . ' ' . $commandArgument . ' 2>&1';
        } else {
            $command = $this->getArtisan() . $commandName . ' 2>&1';
        }

        $namespace = '';

        if (in_array($commandName, $this->nonModuleCommands, true)) {
            $command = $this->getArtisan() . $commandName . ' ' . $name . ' 2>&1';

            if ($commandName === 'make:action') {
                $subFolder = '';

                if (
                    false !== stripos($name, '--resource') ||
                    false !== stripos($name, '--api') ||
                    false !== stripos($name, '--actions')
                ) {
                    $subFolder = "\\\\" . ucfirst(explode(' ', $name)[0]);
                }

                $namespace = "\\\\Modules\\\\$moduleName\\\\Http\\\\Actions$subFolder";

                $command = $this->getArtisan() . $commandName . ' ' . $name . " --namespace=$namespace" . ' 2>&1';
            }
        }

        $result = shell_exec($command);

        if ($result) {
            if (in_array($commandName, $this->nonModuleCommands, true)) {
                if ($commandName === 'make:widget') {

                    if (file_exists(base_path('resources/views/widgets'))) {
                        rename(
                            base_path('resources/views/widgets'),
                            base_path('Modules/' . $moduleName . '/Resources/views/widgets')
                        );
                    }

                    if (file_exists(base_path('app/Widgets'))) {
                        if (
                        rename(
                            base_path('app/Widgets'),
                            base_path('Modules/' . $moduleName . '/Widgets')
                        )
                        ) {
                            $message = 'Widget created successfully!';
                        }
                    }

                } elseif ($commandName === 'make:action') {
                    list($folder) = explode("\n", strstr($result, 'Path: '));
                    $folder = trim(str_replace('Path: ', '', $folder));

                    $namespace = base_path(ltrim(str_replace('\\\\', '\\', $namespace), '\\'));

                    $files = glob("$folder/*.*");

                    foreach ($files as $file) {

                        if (!File::exists($namespace)) {
                            File::makeDirectory($namespace, 0755, true);
                        }

                        rename($file, $namespace . DIRECTORY_SEPARATOR . basename($file));
                    }

                    File::deleteDirectory(app_path('Modules'));

                    $result = str_replace('\\app', '', $result);
                }
            }

            flash($message ?: nl2br($result), 'success');

            if ($commandName === 'module:make') {
                shell_exec($this->getArtisan() . 'module:setup');
            }
        }

        return redirect()->back();
    }

    protected function getArtisan(): string
    {
        $php = 'php';
        $result = shell_exec("$php -v 2>&1");
        $isPHP = stripos($result, 'php') !== false;

        if (!$isPHP) {
            $php = 'php-cli';
            $result = shell_exec("$php -v 2>&1");
            $isPHP = stripos($result, 'php') !== false;
        }

        if (!$isPHP) {
            $php = PHP_BINARY;
            $result = shell_exec("$php -v 2>&1");
            $isPHP = stripos($result, 'php') !== false;
        }

        if (!$isPHP) {
            $php = PHP_BINARY . '-cli';
            $result = shell_exec("$php -v 2>&1");
            $isPHP = stripos($result, 'php') !== false;
        }

        if (!$isPHP) {
            throw new \RuntimeException('Error: Could not find PHP binary.');
        }

        return $php . ' ' . base_path() . '/artisan ';
    }

    public function deleteMigratedFiles(): void
    {
        $dir = base_path() . '/database/migrations';
        $allFiles = glob($dir . '/*.php', GLOB_NOSORT);

        if ($allFiles) {
            $migrations = DB::table('migrations')->select('migration')->get()->pluck('migration')->toArray();
            $migrations = array_map(array($this, 'removeNumber'), $migrations);

            if ($migrations) {
                foreach ($allFiles as $file) {
                    $fileName = $this->removeNumber(pathinfo(basename($file))['filename']);

                    if (false !== in_array($fileName, $migrations, true)) {
                        @unlink($file);
                    }
                }
            }
        }
    }

    protected function removeNumber(string $string)
    {
        return preg_replace('/\d+/', '', $string);
    }

    protected function optimize(): void
    {
        Artisan::call('clear-compiled');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('app:cleanup');
    }
}
