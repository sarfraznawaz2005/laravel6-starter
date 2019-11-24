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
     * @param $saved
     * @return mixed
     */
    protected function html($saved)
    {
        if (!$saved) {
            return flashBackErrors($this->errors);
        }

        return flashBack(self::MESSAGE_UPDATE);
    }

    /**
     * Response to be returned in case of API request.
     *
     * @param $saved
     * @return mixed
     */
    protected function json($saved)
    {
        if (!$saved) {
            return response()->json(['result' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json($this->task, Response::HTTP_OK);
    }
}
