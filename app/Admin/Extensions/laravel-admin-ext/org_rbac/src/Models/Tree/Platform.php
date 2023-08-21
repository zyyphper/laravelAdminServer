<?php

namespace Encore\OrgRbac\Models\Tree;


use Encore\OrgRbac\Traits\ModelRelationTree;
use Encore\OrgRbac\Models\Platform AS BaseModel;

class Platform extends BaseModel
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
        $relationTreeModel = config('org.database.companies_tree');
        $this->setTitleColumn('name');
        $this->setRelationModelTree($relationTreeModel,'platform_id');
    }
}
