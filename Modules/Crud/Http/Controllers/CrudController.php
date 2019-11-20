<?php
/**
 * Created by PhpStorm.
 * User: Sarfraz
 * Date: 1/20/2017
 * Time: 12:30 PM
 */

namespace Modules\Crud\Http\Controllers;

use DB;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

class CrudController
{
    protected $nonModuleCommands = [
        'make:action',
        'make:widget'
    ];

    public function index()
    {
        title('Module Manager');

        $migrationsPending = $this->areMigrationsPending();

        return view('crud::pages.index', compact('migrationsPending'));
    }

    /**
     * Create new module
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->runCommand('module:make', $request->get('name'));
    }

    public function publish(): \Illuminate\Http\RedirectResponse
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

    protected function optimize(): void
    {
        $output = '';

        Artisan::call('clear-compiled');
        $output .= Artisan::output();
        Artisan::call('cache:clear');
        $output .= Artisan::output();
        Artisan::call('view:clear');
        $output .= Artisan::output();
        Artisan::call('config:clear');
        $output .= Artisan::output();
        Artisan::call('app:cleanup');
        $output .= Artisan::output();

        //echo $output;exit;
    }

    public function toggleStatus($moduleName)
    {
        if ($moduleName !== 'Core' && $moduleName !== 'Crud') {
            if (Module::has($moduleName)) {
                $module = Module::find($moduleName);

                if ($module) {
                    $status = 'Enabled';
                    $isEnabled = $module->isStatus(1);

                    if ($isEnabled) {
                        $status = 'Disabled';
                        $module->disable();
                    } else {
                        $module->enable();
                    }

                    flash("Module $status Successfully!", 'success');
                    return redirect()->back();
                }
            }
        }

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createFile(Request $request)
    {
        $name = trim(ucwords($request->get('name')));
        $commandArgument = $name . ' ' . $request->get('module');

        return $this->runCommand($request->get('command'), $commandArgument);
    }

    public function destroy($moduleName)
    {
        if (Module::has($moduleName)) {
            if (!in_array($moduleName, Module::getSystemModules(), true)) {
                $module = Module::find($moduleName);

                if ($module && $module->delete()) {
                    flash('Deleted Successfully!', 'success');
                    return redirect()->back();
                }
            }
        }

        return redirect()->back();
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

    protected function returnBackWithError($error)
    {
        return redirect()->back()->withErrors([
            'error' => $error
        ]);
    }

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

                        if (!File::exists( $namespace)) {
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

    /**
     * runs only new migrations
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function migrate(): \Illuminate\Http\RedirectResponse
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

        return redirect()->back();
    }

    /**
     * runs only new migrations
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function compile(): \Illuminate\Http\RedirectResponse
    {
        set_time_limit(0);

        $type = 'dev';

        if (app()->environment(['prod', 'production', 'live'])) {
            $type = 'prod';
        }

        $output = shell_exec("npm run $type 2>&1");
        Artisan::call('module:publish');

        if (false !== stripos($output, 'Compiled successfully')) {
            flash("npm run $type process finished successfully", 'success');
        } else {
            flash("npm run $type process failed", 'danger');
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

    protected function areMigrationsPending()
    {
        Artisan::call('migrate:status');
        $output = Artisan::output();

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
