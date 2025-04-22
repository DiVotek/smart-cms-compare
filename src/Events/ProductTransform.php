<?php

namespace SmartCms\Compare\Events;

use SmartCms\Compare\Services\CompareService;

class ProductTransform
{
    public function __invoke(&$dto)
    {
        $dto['is_compare'] = CompareService::check($dto['id']);
    }
}
