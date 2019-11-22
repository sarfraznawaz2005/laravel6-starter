<?php

namespace Modules\Crud\Http\Actions\Crud;

use Sarfraznawaz2005\Actions\Action;

class CreateFileCrud extends Action
{
    use CrudTrait;

    public function __invoke()
    {
        $name = ucwords(trim(request()->get('name')));
        $commandArgument = $name . ' ' . request()->get('module');

        return $this->runCommand(request()->get('command'), $commandArgument);
    }
}
