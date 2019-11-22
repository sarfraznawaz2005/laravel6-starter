<?php

namespace Modules\Admin\Http\Actions\User;

use Modules\Admin\DataTables\UserDataTable;
use Sarfraznawaz2005\Actions\Action;

class IndexUser extends Action
{
    public function __invoke(UserDataTable $dataTable)
    {
        title('Users');

        return $dataTable->render('admin::pages.user.index');
    }
}
