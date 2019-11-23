<?php

namespace Modules\Task\Http\Actions\Task;

use Illuminate\Http\Response;
use Modules\Task\Models\Task;
use Sarfraznawaz2005\Actions\Action;

class UpdateTask extends Action
{
    /**
     * Define any validation rules.
     *
     * @return array
     */
    protected $rules = [
        'description' => 'required|min:5'
    ];

    /**
     * Perform the action.
     *
     * @param Task $task
     * @return mixed
     */
    public function __invoke(Task $task)
    {
        return $this->update($task);
    }

    /**
     * Response to be returned in case of web request.
     *
     * @return mixed
     */
    protected function html()
    {
        if (!$this->result) {
            return back()->withInput()->withErrors($this->errors);
        }

        noty(self::MESSAGE_UPDATE, 'success');
        return back();
    }

    /**
     * Response to be returned in case of API request.
     *
     * @return mixed
     */
    protected function json()
    {
        if (!$this->result) {
            return response()->json(['result' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['result' => true], Response::HTTP_OK);
    }
}
