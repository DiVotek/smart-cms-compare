<?php

namespace SmartCms\Compare\Routes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SmartCms\Compare\Services\CompareService;
use SmartCms\Core\Services\ScmsResponse;
use SmartCms\Store\Models\Product;

class CompareHandler
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:' . Product::getDb() . ',id',
        ]);
        if ($validator->fails()) {
            return new ScmsResponse(status: false, errors: $validator->errors()->toArray());
        }
        CompareService::toggle($request->input('product_id', 0));
        return new ScmsResponse(status: true);
    }
}
