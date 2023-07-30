<?php

use \Encore\OrgRbac\Http\Controllers\OrgController;

Route::get('org', OrgController::class.'@index');
