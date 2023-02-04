<?php

use Illuminate\Support\Facades\Route;
use Resham\NovaDependentFilter\Http\Controllers\FilterController;
 
Route::controller(FilterController::class)->group(function () {
    Route::get('/{resource}/filters/options', 'getFilterOptions')->name('filter.options');
	Route::get('/{resource}/lens/{lens}/filters/options', 'getLensFilterOptions')->name('lens.filter.options');
});