<?php

namespace Modules\Task\Http\Actions\Task;

use Illuminate\Http\Response;
use Modules\Task\DataTables\TaskDataTable;
use Modules\Task\Models\Task;
use Sarfraznawaz2005\Actions\Action;

class IndexTask extends Action
{
    protected $task;

    public function __invoke(Task $task)
    {
        title('Task List');

        $this->task = $task;

        return (new TaskDataTable())->render('task::pages.task.index');
    }

    /**
     * Response to be returned in case of API request.
     *
     * @return mixed
     */
    protected function json()
    {
        return response()->json($this->task->all(), Response::HTTP_OK);
    }
}
