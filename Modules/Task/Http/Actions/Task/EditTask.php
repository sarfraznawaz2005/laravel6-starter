<?php

namespace Modules\Task\Http\Actions\Task;

use Illuminate\Http\Response;
use Modules\Task\Models\Task;
use Sarfraznawaz2005\Actions\Action;

class EditTask extends Action
{
    /**
     * Perform the action.
     *
     * @param Task $task
     * @return mixed
     */
    public function __invoke(Task $task)
    {
        return $task;
    }

    /**
     * Response to be returned in case of web request.
     *
     * @param $task
     * @return mixed
     */
    protected function html($task)
    {
        title('Edit Task');

        return view('task::pages.task.edit')->with(['task' => $task]);
    }

    /**
     * Response to be returned in case of API request.
     *
     * @param $task
     * @return mixed
     */
    protected function json($task)
    {
        return response()->json($task, Response::HTTP_OK);
    }
}
