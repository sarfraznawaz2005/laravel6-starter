<?php

namespace Modules\Task\Http\Actions\Task;

use Illuminate\Http\Response;
use Modules\Task\DataTables\TaskDataTable;
use Modules\Task\Models\Task;
use Sarfraznawaz2005\Actions\Action;

class IndexTask extends Action
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
     * @return mixed
     */
    protected function html()
    {
        title('Task List');

        return (new TaskDataTable())->render('task::pages.task.index');
    }

    /**
     * Response to be returned in case of API request.
     *
     * @param $task
     * @return mixed
     */
    protected function json($task)
    {
        return response()->json($task->all(), Response::HTTP_OK);
    }
}
