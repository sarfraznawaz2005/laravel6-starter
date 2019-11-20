<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Response;
use Modules\Admin\DataTables\UserDataTable;

class UserController
{
    /**
     * Display a listing of the resource.
     * @param UserDataTable $dataTable
     * @return Response
     */
    public function __invoke(UserDataTable $dataTable)
    {
        title('Users');

        return $dataTable->render('admin::pages.user.index');
    }
}
