<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MasterItemController;
use App\Http\Controllers\MasterIndirectCostController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskTimelineController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('projects', ProjectController::class);
Route::get('/projects/{project}/tasks/{task}', [TaskTimelineController::class, 'show'])->name('projects.tasks.show');

Route::get('/master-data', function () {
    return view('master-data.new-index');
})->name('master-data.index');

Route::get('/master-items', function () {
    return view('master-data.index', [
        'defaultTab' => 'items',
        'items' => \App\Models\MasterItem::with('category')->get(),
        'costs' => \App\Models\MasterIndirectCost::all(),
        'categories' => \App\Models\MasterCategory::all()
    ]);
})->name('master-data.items.index');

Route::post('/master-items', [\App\Http\Controllers\MasterItemController::class, 'store'])->name('master-items.store');
Route::put('/master-items/{masterItem}', [\App\Http\Controllers\MasterItemController::class, 'update'])->name('master-items.update');
Route::delete('/master-items/{masterItem}', [\App\Http\Controllers\MasterItemController::class, 'destroy'])->name('master-items.destroy');

Route::get('/master-indirect-costs', function () {
    return view('master-data.index', [
        'defaultTab' => 'indirect',
        'items' => \App\Models\MasterItem::with('category')->get(),
        'costs' => \App\Models\MasterIndirectCost::all(),
        'categories' => \App\Models\MasterCategory::all()
    ]);
})->name('master-data.indirect.index');

Route::post('/master-indirect-costs', [\App\Http\Controllers\MasterIndirectCostController::class, 'store'])->name('master-indirect-costs.store');
Route::put('/master-indirect-costs/{masterIndirectCost}', [\App\Http\Controllers\MasterIndirectCostController::class, 'update'])->name('master-indirect-costs.update');
Route::delete('/master-indirect-costs/{masterIndirectCost}', [\App\Http\Controllers\MasterIndirectCostController::class, 'destroy'])->name('master-indirect-costs.destroy');

Route::get('/master-categories', function () {
    return view('master-data.index', [
        'defaultTab' => 'categories',
        'items' => \App\Models\MasterItem::with('category')->get(),
        'costs' => \App\Models\MasterIndirectCost::all(),
        'categories' => \App\Models\MasterCategory::all()
    ]);
})->name('master-data.categories.index');

Route::post('/master-categories', [\App\Http\Controllers\MasterCategoryController::class, 'store'])->name('master-categories.store');
Route::put('/master-categories/{category}', [\App\Http\Controllers\MasterCategoryController::class, 'update'])->name('master-categories.update');
Route::delete('/master-categories/{category}', [\App\Http\Controllers\MasterCategoryController::class, 'destroy'])->name('master-categories.destroy');
