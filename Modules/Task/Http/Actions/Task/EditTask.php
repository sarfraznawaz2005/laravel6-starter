<?php

namespace Modules\Task\Http\Actions\Task;

use Illuminate\Http\Response;
use Modules\Task\Models\Task;
use Sarfraznawaz2005\Actions\Action;

class EditTask extends Action
{
    protected $task;

    /**
     * Perform the action.
     *
     * @param Task $task
     * @return mixed
     */
    public function __invoke(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Response to be returned in case of web request.
     *
     * @return mixed
     */
    protected function html()
    {
        title('Edit Task');

        return view('task::pages.task.edit')->with(['task' => $this->task]);
    }

    /**
     * Response to be returned in case of API request.
     *
     * @return mixed
     */
    protected function json()
    {
        return response()->json($this->task, Response::HTTP_OK);
    }
}
