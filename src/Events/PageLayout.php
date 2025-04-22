<?php

namespace SmartCms\Compare\Events;

use Illuminate\Database\Eloquent\Builder;
use SmartCms\Core\Models\Layout;
use SmartCms\Core\Models\Page;

class PageLayout
{
    public function __invoke(Builder $query, Page $page)
    {
        $comparePage = setting('pages.compare', 0);
        if ($page->id == $comparePage) {
            $compareLayouts = Layout::query()->where('path', 'like', 'compare%')->get();
            return $query->whereIn('id', $compareLayouts->pluck('id'));
        }
    }
}
