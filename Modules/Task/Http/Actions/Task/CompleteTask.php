<?php

namespace Modules\Task\Http\Actions\Task;

use Illuminate\Http\Response;
use Modules\Task\Models\Task;
use Sarfraznawaz2005\Actions\Action;

class CompleteTask extends Action
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

        $this->task->completed = !$this->task->completed;

        return $task->save();
    }

    /**
     * Response to be returned in case of web request.
     *
     * @return mixed
     */
    protected function html()
    {
        if (!$this->result) {
            return flashBackErrors($this->errors);
        }

        return flashBack(self::MESSAGE_UPDATE);
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

        return response()->json($this->task, Response::HTTP_OK);
    }
}
