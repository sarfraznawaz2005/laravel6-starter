<?php

namespace Modules\Crud\Http\Actions\Crud;

use Sarfraznawaz2005\Actions\Action;

class CreateFileCrud extends Action
{
    use CrudTrait;

    /**
     * Define any validation rules.
     */
    protected $rules = [
        'name' => 'required|min:3',
        'module' => 'required',
        'command' => 'required'
    ];

    public function __invoke()
    {
        $name = ucwords(trim(request()->get('name')));
        $commandArgument = $name . ' ' . request()->get('module');

        return $this->runCommand(request()->get('command'), $commandArgument);
    }
}
