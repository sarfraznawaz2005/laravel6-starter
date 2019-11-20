<?php

namespace Modules\Task\Http\Actions\Task;

use Illuminate\Http\Response;
use Modules\Task\DataTables\TaskDataTable;
use Modules\Task\Models\Task;
use Sarfraznawaz2005\Actions\Action;

class IndexTask extends Action
{
    protected $dataTable;

    public function __invoke(TaskDataTable $dataTable)
    {
        $this->dataTable = $dataTable;

        return $this->sendResponse();
    }

    /**
     * Response to be returned in case of web request.
     *
     * @return mixed
     */
    protected function htmlResponse()
    {
        title('Task List');

        return $this->dataTable->render('task::pages.task.index');
    }

    /**
     * Response to be returned in case of API request.
     *
     * @return mixed
     */
    protected function jsonResponse()
    {
        $tasks = Task::where('user_id', user()->id)->get();

        return response()->json($tasks, Response::HTTP_OK);
    }
}
