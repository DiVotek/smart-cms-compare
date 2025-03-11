<?php

namespace SmartCms\Compare\Events;

use SmartCms\Compare\Services\CompareService;
use SmartCms\Store\Repositories\Product\ProductDto;

class ProductTransform
{
    public function __invoke(ProductDto $dto)
    {
        $dto->setExtraValue('is_compare', CompareService::check($dto->id));
    }
}
