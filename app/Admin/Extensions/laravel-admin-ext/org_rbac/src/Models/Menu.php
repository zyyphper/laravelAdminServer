<?php

namespace Encore\OrgRbac\Models;


use Encore\Admin\Models\Menu AS BaseMenu;

class Menu extends BaseMenu
{
    protected $fillable = ['parent_id', 'order', 'title', 'icon', 'uri'];
}
