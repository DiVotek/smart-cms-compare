<?php

namespace SmartCms\Compare\Events;

use SmartCms\Compare\CompareResource;
use SmartCms\Core\Models\Page;

class PageView
{
    public function __invoke(mixed &$resource, Page $page)
    {
        $layout = $page?->layout;
        if (! $layout) {
            return;
        }
        if (str_contains($layout->path, 'compare.')) {
            $resource = CompareResource::make($page)->get();
        }
    }
}
