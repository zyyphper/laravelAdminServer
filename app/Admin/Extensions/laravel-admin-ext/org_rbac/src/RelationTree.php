<?php

namespace Encore\OrgRbac;

use Encore\Admin\Admin;
use Encore\Admin\Tree;
use Illuminate\Database\Eloquent\Model;

class RelationTree extends Tree
{
    protected $homePath;
    /**
     * View of tree to render.
     *
     * @var string
     */
    protected $view = [
        'tree'   => 'org_rbac::relationTree.tree',
        'branch' => 'org_rbac::relationTree.branch',
    ];

    public function __construct(Model $model = null, Closure $callback = null)
    {
        $this->homePath = \request()->getPathInfo();
        parent::__construct($model, $callback);
    }

    public function setHomePath($path)
    {
        $this->homePath = $path;
    }

    public function render()
    {
        view()->share([
            'path'           => $this->path,
            'homePath'       => $this->homePath,
            'keyName'        => $this->model->getKeyName(),
            'column'         => "type",
            'branchView'     => $this->view['branch'],
            'branchCallback' => $this->branchCallback,
            'model'          => get_class($this->model),
        ]);

        return Admin::view($this->view['tree'], [
            'id'         => $this->elementId,
            'tools'      => $this->tools->render(),
            'items'      => $this->getItems(),
            'useCreate'  => $this->useCreate,
            'useSave'    => $this->useSave,
            'url'        => url($this->path),
            'options'    => $this->options,
        ]);
    }

    /**
     * Set view of tree.
     *
     * @param array $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }
}
