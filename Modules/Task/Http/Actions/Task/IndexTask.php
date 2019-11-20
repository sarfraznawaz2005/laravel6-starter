<?php

namespace Modules\Task\Http\Actions\Task;

use Illuminate\Http\Response;
use Modules\Task\DataTables\TaskDataTable;
use Sarfraznawaz2005\Actions\Action;

class IndexTask extends Action
{
    public function __invoke()
    {
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

        return (new TaskDataTable())->render('task::pages.task.index');
    }

    /**
     * Response to be returned in case of API request.
     *
     * @return mixed
     */
    protected function jsonResponse()
    {
        return response()->json(null, Response::HTTP_OK);
    }
}
