<?php

namespace Encore\OrgRbac\Models;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['platform_id','name','slug'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.roles_table'));

        parent::__construct($attributes);
    }
}
