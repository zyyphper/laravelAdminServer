<?php

namespace Encore\OrgRbac\Traits;


use Encore\Admin\Facades\Admin;

trait PlatformPermission
{
    protected $platformId;
    protected $companyId;
    protected $data;

    public function getPlatformId()
    {
        if (!empty($this->platformId)) {
            return $this->platformId;
        }
        return Admin::user()->platform_id;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function toPluck()
    {
        return $this->data;
    }

    protected function setRootPluck($data) {
        $data[0] = "ROOT";
        return $this;
    }

    public function getCompany()
    {
        $companyModel = config('org.database.companies_model');
        $this->data = $companyModel::where('platform_id',$this->getPlatformId())->pluck('name','id');
        return $this;
    }

    public function getDepartment()
    {
        $departmentModel = config('org.database.departments_model');
        $departmentModel = new $departmentModel();
        if ($this->companyId) {
            $departmentModel = $departmentModel->where('company_id',$this->getCompanyId());
        } else {
            $departmentModel = $departmentModel->with(['company'=>function ($query) {
                $query->where('platform_id',$this->getPlatformId());
            }]);
        }
        $this->data = $departmentModel->pluck('name','id');
        return $this;
    }

    public function getTreeToCompanyAndDepartment()
    {
        $companyData = $this->getCompany()->formatDataToTree();
        foreach ($companyData as &$data) {
            $this->companyId = $data['value'];
            $data['children'] = $this->getDepartment()->formatDataToTree();
        }

        return $companyData;
    }

    public function formatDataToTree()
    {
        $result = [];
        foreach ($this->data as $id => $name) {
            array_push($result,[
                'text' => $name,
                'value' => "$id",
            ]);
        }
        return $result;
    }

}
