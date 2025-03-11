<?php

namespace SmartCms\Compare\Events;

use SmartCms\Compare\Services\CompareService;
use SmartCms\Core\Components\Pages\PageComponent;

class PageView
{
    public function __invoke(PageComponent $component)
    {
        $layout = $component->layout;
        if (! $layout) {
            return;
        }
        if ($layout->path == 'compare/compare') {
            $component->dto = CompareService::make();
        }
    }
}
