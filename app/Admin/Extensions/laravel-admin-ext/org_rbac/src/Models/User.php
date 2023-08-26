<?php

namespace Encore\OrgRbac\Models;


use Encore\Admin\Models\Administrator;

class User extends Administrator
{
    protected $fillable = ['id','platform_id','username', 'password','name', 'is_admin'];

    protected $primaryKey = 'id';
    public $incrementing = false;

    public function departments()
    {
        $pivotTable = config('org.database.duties_table');
        $relatedModel = config('org.database.departments_model');
        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'department_id')->withPivot('id');
    }


    public function duties()
    {
        $dutyModel = config('org.database.duties_model');
        return $this->hasMany($dutyModel,'user_id');
    }

    public function info()
    {
        $userInfoModel = config('org.database.user_infos_model');
        return $this->hasOne($userInfoModel,'user_id');
    }

    public function roles()
    {
        $pivotTable = config('org.database.duties_table');
        $relatedModel = config('org.database.roles_model');
        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'department_id')->withPivot('id');
    }
}
