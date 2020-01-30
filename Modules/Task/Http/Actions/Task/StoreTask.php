<?php

namespace Modules\Task\Http\Actions\Task;

use Illuminate\Http\Response;
use Modules\Task\Models\Task;
use Sarfraznawaz2005\Actions\Action;

class StoreTask extends Action
{
    /**
     * Transform request data
     *
     * @return array
     */
    public function transform(): array
    {
        return [
            'user_id' => user()->id ?? (request()->user_id ?? 0),
        ];
    }

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
        return $this->create($task);
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

        return flashBack(self::MESSAGE_CREATE);
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

        return response()->json(['result' => true], Response::HTTP_CREATED);
    }
}
