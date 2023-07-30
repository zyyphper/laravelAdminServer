<?php

namespace Encore\OrgRbac\Models;


use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use ModelTree;
    protected $fillable = ['parent_id','company_id','name','leader','order'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $this->setConnection($connection);

        $this->setTable(config('admin.database.departments_table'));
        $this->setTitleColumn('name');
        parent::__construct($attributes);
    }

    public function users()
    {
        $pivotTable = config('admin.database.duties_model');
        $relatedModel = config('admin.database.users_model');
        return $this->belongsToMany($relatedModel, $pivotTable, 'department_id', 'user_id');
    }

    public function duties()
    {
        $dutyModel = config('admin.database.duties_model');
        return $this->hasMany($dutyModel,'department_id');
    }
}
