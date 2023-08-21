<?php

namespace Encore\OrgRbac\Models\Tree;


use Encore\OrgRbac\Traits\ModelRelationTree;
use Encore\OrgRbac\Models\Department AS BaseModel;

class Department extends BaseModel
{
    use ModelRelationTree;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTitleColumn('name');
    }

}
