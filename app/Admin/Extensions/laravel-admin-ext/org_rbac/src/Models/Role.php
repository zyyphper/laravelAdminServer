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
        $connection = config('org.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('org.database.roles_table'));

        parent::__construct($attributes);
    }

    public function duties()
    {
        $pivotTable = config('org.database.role_duty_table');
        $relatedModel = config('org.database.duties_model');
        return $this->belongsToMany($relatedModel, $pivotTable, 'role_id', 'duty_id');
    }
}
