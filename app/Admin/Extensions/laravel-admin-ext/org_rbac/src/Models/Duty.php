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
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.duties_table'));
        parent::__construct($attributes);
    }

    public function user()
    {
        $userModel = config('admin.database.users_model');
        return $this->hasOne($userModel,'id','user_id');
    }

}
