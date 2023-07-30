<?php

namespace Encore\OrgRbac;

use Encore\Admin\Extension;

class OrgRbac extends Extension
{
    public $name = 'org_rbac';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Orgrbac',
        'path'  => 'org_rbac',
        'icon'  => 'fa-gears',
    ];
}
