<?php

namespace Modules\Crud\Http\Actions\Crud;

use Sarfraznawaz2005\Actions\Action;

class StoreCrud extends Action
{
    use CrudTrait;

    /**
     * Define any validation rules.
     */
    protected $rules = [
        'name' => 'required|min:3'
    ];

    /**
     * Perform the action.
     *
     * @return mixed
     */
    public function __invoke()
    {
        return $this->runCommand('module:make', request()->get('name'));
    }
}
