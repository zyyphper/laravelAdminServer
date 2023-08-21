<?php


Route::group([
    'prefix' => config('org.route.prefix'),
], function (Illuminate\Routing\Router $router) {
    $router->resource('platforms', \Encore\OrgRbac\Http\Controllers\PlatformController::class);
    $router->resource('companies', \Encore\OrgRbac\Http\Controllers\CompanyController::class);
    $router->resource('departments', \Encore\OrgRbac\Http\Controllers\DepartmentController::class);
    $router->resource('users', \Encore\OrgRbac\Http\Controllers\UserController::class);
    $router->resource('organizations', \Encore\OrgRbac\Http\Controllers\OrgController::class);

});


Route::group([
    'prefix' => 'api',
], function (Illuminate\Routing\Router $router) {
    $router->get('organizations/getCompanyList', \Encore\OrgRbac\Http\Controllers\OrgController::class."@getCompanyList");
    $router->get('organizations/getDepartmentList', \Encore\OrgRbac\Http\Controllers\OrgController::class."@getDepartmentList");
});
