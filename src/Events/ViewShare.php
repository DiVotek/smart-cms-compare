<?php

namespace SmartCms\Compare\Events;

use SmartCms\Compare\Services\CompareService;
use SmartCms\Core\Models\Page;

class ViewShare
{
    public function __invoke(array &$data)
    {
        $comparePage = Page::query()->where('id', setting('pages.compare', 0))->first() ?? new Page(['name' => 'Compare', 'slug' => 'compare']);
        $compareCount = CompareService::getProductsCount();
        $data['compare_page'] = $comparePage->route();
        $data['compare_count'] = $compareCount;
    }
}
