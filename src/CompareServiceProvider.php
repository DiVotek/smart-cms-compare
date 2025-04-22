<?php

namespace SmartCms\Compare;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SmartCms\Compare\Events\PageLayout;
use SmartCms\Compare\Events\ProductTransform;
use SmartCms\Compare\Events\PageView;
use SmartCms\Compare\Events\ViewShare;
use SmartCms\Core\Admin\Resources\StaticPageResource;
use SmartCms\Core\SmartCmsServiceProvider;
use SmartCms\Store\Resources\Product\ProductEntityResource;
use SmartCms\Store\Resources\Product\ProductResource;

class CompareServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            Commands\Install::class,
        ]);
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        SmartCmsServiceProvider::registerHook('view_share', ViewShare::class);
    }

    public function boot()
    {
        ProductResource::registerHook('transform.data', ProductTransform::class);
        ProductEntityResource::registerHook('transform.data', ProductTransform::class);
        Event::listen('cms.page.construct', PageView::class);
        StaticPageResource::registerHook('page.layout', PageLayout::class);
    }
}
