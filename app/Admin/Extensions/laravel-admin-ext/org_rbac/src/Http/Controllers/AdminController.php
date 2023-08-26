<?php

namespace Encore\OrgRbac\Http\Controllers;

use Encore\Admin\Http\Controllers\AdminController AS BaseAdminController;
use Illuminate\Http\Request;

class AdminController extends BaseAdminController
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function actionSuccess($message)
    {
        return json_encode([
            'status' => true,
            'swal' => [
                "icon" => "success",
                "title" => $message
            ]
        ]);
    }

    public function actionError($message)
    {
        return json_encode([
            'status' => false,
            'swal' => [
                "icon" => "fail",
                "title" => $message
            ]
        ]);
    }
}
