<?php

namespace Encore\OrgRbac;

use Encore\OrgRbac\Services\DatabasePrimaryKeyGenerateService;
use Encore\OrgRbac\Services\SnowFlakeService;
use Illuminate\Support\ServiceProvider;

class OrgRbacServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(OrgRbac $extension)
    {
        if (! OrgRbac::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'org_rbac');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/org_rbac')],
                'org_rbac'
            );
        }

        $this->app->booted(function () {
            OrgRbac::routes(__DIR__.'/../routes/web.php');
        });

        $this->app->singleton('primaryKeyGenerate',function () {
            return new DatabasePrimaryKeyGenerateService();
        });
    }
}
