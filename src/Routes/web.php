<?php

use Illuminate\Support\Facades\Route;
use SmartCms\Compare\Routes\CompareHandler;
use SmartCms\Compare\Services\CompareService;
use SmartCms\Core\Services\ScmsResponse;

Route::get('/api/compare', CompareHandler::class)->middleware(['lang', 'web'])->name('compare.index');
Route::get('/api/compare/count', function () {
    return new ScmsResponse(200, [
        'count' => CompareService::getProductsCount(),
    ]);
})->middleware(['lang', 'web'])->name('compare.index');
