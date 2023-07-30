<?php

namespace Encore\OrgRbac\Models;


use Encore\Admin\Facades\Admin;
use Encore\AdminRbac\Traits\ModelRelationTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Platform extends Model
{
    use ModelRelationTree;
    protected $fillable = ['name', 'status'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $relationModel = config('admin.database.companies_model');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.platforms_table'));
        $this->setTitleColumn('name');
        $this->setRelationModelTree($relationModel,'company_id');

        parent::__construct($attributes);
    }

    public function companies()
    {
        $companyModel = config('admin.database.companies_model');
        return $this->hasMany($companyModel);
    }

    /**
     * @return array
     */
    public function allNodes(): array
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $orderColumn = DB::connection($connection)->getQueryGrammar()->wrap($this->orderColumn);

        $byOrder = 'ROOT ASC,'.$orderColumn;

        $query = static::query();

        if (config('admin.check_menu_roles') !== false) {
            $query->with('roles');
        }
        if ($this->isRootPlatform()) {
            return $query->selectRaw('*, '.$orderColumn.' ROOT')->orderByRaw($byOrder)->get()->toArray();
        }

        return $query->whereHas('platformConfigs',function ($query) {
            $query->where('platform_id',Admin::user()->platform_id);
        })->selectRaw('*, '.$orderColumn.' ROOT')
            ->orderByRaw($byOrder)
            ->get()->toArray();
    }
}
