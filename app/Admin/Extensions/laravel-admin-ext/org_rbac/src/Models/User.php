<?php

namespace Encore\OrgRbac\Models;


use Encore\Admin\Models\Administrator;

class User extends Administrator
{
    protected $fillable = ['platform_id','username', 'password','name', 'is_admin'];

    public function departments()
    {
        $pivotTable = config('admin.database.duties_model');
        $relatedModel = config('admin.database.departments_model');
        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'department_id');
    }

    public function duties()
    {
        $dutyModel = config('admin.database.duties_model');
        return $this->hasMany($dutyModel,'user_id');
    }
}
