<?php

namespace Encore\OrgRbac\Models;


use Illuminate\Database\Eloquent\Model;

class Duty extends Model
{
    protected $fillable = ['user_id','department_id','department_type'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('org.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('org.database.duties_table'));
        parent::__construct($attributes);
    }

    public function user()
    {
        $userModel = config('org.database.users_model');
        return $this->hasOne($userModel,'id','user_id');
    }

    public function department()
    {
        $department = config('org.database.departments_model');
        return $this->hasOne($department,'id','department_id');
    }

    public function company()
    {
        $company = config('org.database.companies.model');
        $department = config('org.database.departments_model');
        return $this->hasOneThrough($company,$department);
    }

}
