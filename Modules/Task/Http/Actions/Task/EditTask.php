<?php

namespace Modules\Task\Http\Actions\Task;

use Illuminate\Http\Response;
use Sarfraznawaz2005\Actions\Action;

class EditTask extends Action
{
    /**
     * Define any validation rules.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * Perform the action.
     *
     * @return mixed
     */
    public function __invoke()
    {
        //

        return $this->sendResponse();
    }

    /**
     * Response to be returned in case of web request.
     *
     * @return mixed
     */
    protected function htmlResponse()
    {
        return 'hi';
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
