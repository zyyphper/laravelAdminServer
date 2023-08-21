<?php

namespace Encore\OrgRbac\Http\Controllers;

use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\OrgRbac\Widgets\Tab;
use Encore\OrgRbac\Models\Enums\OrgType;
use Encore\OrgRbac\RelationTree;
use Encore\OrgRbac\TabTable\TabTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrgController extends AdminController
{
    protected $type;
    protected $mainId;
    protected $tab;
    protected $platformModel;
    protected $companyModel;
    protected $departmentModel;
    protected $userModel;


    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->type =  $this->request->input("type");
        $this->mainId =  $this->request->input("main_id");
        $this->tab = $this->request->input("tab",0);
        $platformModel = config('org.database.platforms_tree');
        $this->platformModel = new $platformModel();
        $companyModel = config('org.database.companies_tree');
        $this->companyModel = new $companyModel();
        $departmentModel = config('org.database.departments_tree');
        $this->departmentModel = new $departmentModel();
        $userModel = config('org.database.users_model');
        $this->userModel = new $userModel();
    }

    public function index(Content $content)
    {
        $content->row(function(Row $row){
            //组织树
            $row->column(4,$this->treeView()->render());
            if (is_null($this->type) || is_null($this->mainId)) {
                $row->column(8,function (Column $column) {
                });
            } else {
                //根据选择的是 平台 公司 部门  动态显示
                $method = OrgType::$index[$this->type];
                $row->column(8,function (Column $column) use($method) {
                    $content = $this->$method();
                    $column->row($content);
               });
           }
        });
        return $content;
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        $platformTreeModel = config('org.database.platforms_tree');

        $tree = new RelationTree(new $platformTreeModel());
        $tree->setHomePath(url("/admin/auth/organizations"));

        $tree->disableCreate();
        $tree->disableSave();
        $tree->setView([
            'tree'   => 'admin::tree',
            'branch' => 'org_rbac::relationTree.branch',
        ]);
        $tree->nestable([
            'maxDepth' => 0, // 设置可拖动层级为 2 层，设置其他参数可参考 jquery.nestable 文档
        ]);


        $tree->branch(function ($branch) {
            //获取当前平台下的公司
            return "<i class='fa'></i>&nbsp;<strong>{$branch['name']}</strong>";
        });

        return $tree;
    }

    public function platform()
    {
        $table = new TabTable($this->companyModel);
        $table->model()->whereHas('platform',function ($query) {
            $query->where($this->platformModel->getKeyName(),$this->mainId);
        });
        $table->setTitle('公司');
        $table->setResourceUrl(url("admin/auth/companies"));
        $table->setCreateHandleParams([
            'platform_id' => $this->mainId
        ]);
        $table->column('id','ID');
        $table->column('name',trans('admin.name'));
        return $table->render();
    }

    public function company()
    {
        $tab = new Tab();
        //子公司TAB
        $companyTable = new TabTable($this->companyModel);
        $companyTable->setTitle("子公司");
        $companyTable->setResourceUrl(url("admin/auth/companies"));
        $companyTable->setBackUrl($this->request->getUri()."&tab=0");
        $companyTable->setCreateHandleParams([
            'parent_id' => $this->mainId
        ]);

        $companyTable->model()->where($this->companyModel->getParentColumn(),$this->mainId);
        $companyTable->column('id',"ID");
        $companyTable->column('name',"名称");
        $tab->add('子公司 ',$companyTable,$this->tab == 0);
        //部门
        $departmentTable = new TabTable($this->departmentModel);
        $departmentTable->setTitle("部门");
        $departmentTable->setResourceUrl(url("admin/auth/departments"));
        $departmentTable->setBackUrl($this->request->getUri()."&tab=1");
        $departmentTable->setCreateHandleParams([
            'company_id' => $this->mainId
        ]);
        $departmentTable->model()->whereHas('company',function($query) {
            $query->where($this->departmentModel->getKeyName(),$this->mainId);
        });
        $departmentTable->column('id',"ID");
        $departmentTable->column('name',"名称");
        $tab->add('部门',$departmentTable,$this->tab == 1);
        return $tab;
    }

    public function department()
    {
        $tab = new Tab();
        //子部门
        $departmentTable = new TabTable($this->departmentModel);
        $departmentTable->setTitle("部门");
        $departmentTable->setResourceUrl(url("admin/auth/departments"));
        $departmentTable->setBackUrl($this->request->getUri()."&tab=0");
        $departmentTable->setCreateHandleParams([
            'parent_id' => $this->mainId
        ]);
        $departmentTable->model()->where($this->departmentModel->getParentColumn(),$this->mainId);
        $departmentTable->column('name',"名称");
        $tab->add('子部门',$departmentTable,$this->tab == 0);
        //用户
        $userTable = new TabTable($this->userModel);
        $userTable->setTitle("用户");
        $userTable->setResourceUrl(url("admin/auth/users"));
        $userTable->setBackUrl($this->request->getUri()."&tab=1");
        $userTable->setCreateHandleParams([
            'department_id' => $this->mainId
        ]);
        $userTable->model()->whereHas('departments',function($query) {
            $query->where('department_id',$this->mainId);
        });
        $userTable->column('name',"名称");
        $tab->add('用户',$userTable,$this->tab == 1);

        return $tab;
    }


    public function getCompanyList(Request $request)
    {
        $platformId = $request->get('q');
        return $this->companyModel->where('platform_id',$platformId)->get(['id',DB::raw('name as text')]);
    }

    public function getDepartmentList(Request $request)
    {
        $companyId = $request->get('q');
        return $this->departmentModel->where('company_id',$companyId)->get(['id',DB::raw('name as text')]);
    }
}
