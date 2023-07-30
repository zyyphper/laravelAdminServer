<?php

use Encore\OrgRbac\Http\Controllers\OrgRbacController;

Route::get('org_rbac', OrgRbacController::class.'@index');