<?php

namespace Encore\OrgRbac\Models;


use Encore\AdminRbac\Traits\ModelRelationTree;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use ModelRelationTree;
    protected $fillable = ['parent_id','platform_id','name','email','phone','order'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $relationModel = config('admin.database.departments_model');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.companies_table'));
        $this->setTitleColumn('name');
        $this->setRelationModelTree($relationModel,'department_id');

        parent::__construct($attributes);
    }

    public function departments()
    {
        $departmentModel = config('admin.database.departments_model');
        return $this->hasMany($departmentModel);
    }
}
