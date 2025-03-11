<?php

use Illuminate\Support\Facades\Route;
use SmartCms\Compare\Routes\CompareHandler;
use SmartCms\Compare\Services\CompareService;

Route::get('/api/compare', CompareHandler::class)->middleware(['lang', 'web'])->name('compare.index');
