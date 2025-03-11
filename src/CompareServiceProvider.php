<?php

namespace SmartCms\Compare;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SmartCms\Compare\Events\ProductEntityTransform;
use SmartCms\Compare\Events\ProductTransform;
use SmartCms\Compare\Events\PageView;
use SmartCms\Compare\Events\ViewShare;

class CompareServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            Commands\Install::class,
        ]);
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        Event::listen('cms.view.share', ViewShare::class);
    }

    public function boot()
    {
        Event::listen('cms.product-entity.transform', ProductEntityTransform::class);
        Event::listen('cms.product.transform', ProductTransform::class);
        Event::listen('cms.page.construct', PageView::class);
    }
}
