<?php

namespace SmartCms\Compare\Events;

use SmartCms\Compare\Services\CompareService;
use SmartCms\Store\Repositories\Product\ProductEntityDto as ProductProductEntityDto;

class ProductEntityTransform
{
    public function __invoke(ProductProductEntityDto $dto)
    {
        $dto->setExtraValue('is_compare', CompareService::check($dto->id));
    }
}
