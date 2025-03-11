<?php

namespace SmartCms\Compare\Events;

use SmartCms\Compare\Services\CompareService;
use SmartCms\Core\Models\Page;

class ViewShare
{
    public function __invoke($view)
    {
        $comparePage = Page::query()->where('id', setting('pages.compare', 0))->first() ?? new Page(['name' => 'Compare', 'slug' => 'compare']);
        $compareCount = CompareService::getProductsCount();
        $view->with('compare_page', $comparePage->route());
        $view->with('compare_count', $compareCount);
    }
}
