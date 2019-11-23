<?php

namespace Modules\Task\Http\Actions\Task;

use Illuminate\Http\Response;
use Modules\Task\Models\Task;
use Sarfraznawaz2005\Actions\Action;

class DestroyTask extends Action
{
    /**
     * Perform the action.
     *
     * @param Task $task
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(Task $task)
    {
        return $this->delete($task);
    }

    /**
     * Response to be returned in case of web request.
     *
     * @return mixed
     */
    protected function html()
    {
        if (!$this->result) {
            return back()->withInput()->withErrors(self::MESSAGE_FAIL);
        }

        noty(self::MESSAGE_DELETE, 'success');
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

        return response()->json(['result' => true], Response::HTTP_NO_CONTENT);
    }
}
