<?php

namespace Encore\OrgRbac\Http\Controllers;

use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Illuminate\Routing\Controller;

class OrgController extends Controller
{
    public function index(Content $content)
    {
        $content->row(function(Row $row) {
            //组织树
            $row->column(4,$this->treeView()->render());
            //根据选择的是 公司 部门 用户 动态显示
            $row->column(8,function (Column $column) {
                $column->row('选项卡');
                $column->row('展示框');
            });
        });
        return $content
            ->title('Title')
            ->description('Description')
            ->body(view('org_rbac::index'));
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        $platformModel = config('admin.database.platforms_model');

        $tree = new Tree(new $platformModel());
        $tree->disableCreate();

        $tree->branch(function ($branch) {
            //获取当前平台下的公司

            $payload = "<i class='fa'></i>&nbsp;<strong>{$branch['title']}</strong>";

//            if (!isset($branch['children'])) {
//                if (url()->isValidUrl($branch['uri'])) {
//                    $uri = $branch['uri'];
//                } else {
//                    $uri = admin_url($branch['uri']);
//                }
//
//                $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
//            }

            return $payload;
        });

        return $tree;
    }
}
