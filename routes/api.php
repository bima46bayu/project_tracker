<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTaskController;
use App\Http\Controllers\TaskItemController;
use App\Http\Controllers\IndirectCostController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SubkonController;
use App\Http\Controllers\Api\BowheerController;
use App\Http\Controllers\Api\MiscController;
use App\Http\Controllers\ProjectIssueController;
use App\Http\Controllers\ProjectDocumentationController;
use App\Http\Controllers\TaskTimelineController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::name('api.')->group(function () {
    // Projects
    Route::apiResource('projects', ProjectController::class);
    Route::get('projects/{project}/s-curve', [ProjectController::class, 'getSCurve'])->name('projects.s-curve');

    // Project Tasks (BOQ)
    Route::apiResource('tasks', ProjectTaskController::class);
    Route::post('tasks/{task}/sync-items', [ProjectTaskController::class, 'syncTaskItems'])->name('tasks.sync-items');

    // Task Timeline (Plan & Realisasi)
    Route::post('tasks/{task}/timeline/plan', [TaskTimelineController::class, 'storePlan']);
    Route::post('tasks/{task}/timeline/plan/lock', [TaskTimelineController::class, 'lockPlan']);
    Route::post('tasks/{task}/timeline/plan/unlock', [TaskTimelineController::class, 'unlockPlan']);
    Route::post('tasks/{task}/timeline/realisasi', [TaskTimelineController::class, 'storeRealisasi']);
    Route::post('tasks/{task}/timeline/realisasi/add-week', [TaskTimelineController::class, 'addExtraWeek']);

    // Task Items (RAB Items)
    Route::apiResource('task-items', TaskItemController::class);

    // Master Data APIs (Legacy)
    Route::apiResource('master-items', App\Http\Controllers\MasterItemController::class);
    Route::apiResource('master-indirect-costs', App\Http\Controllers\MasterIndirectCostController::class);

    // Project Indirect Costs
    Route::apiResource('indirect-costs', IndirectCostController::class);
    Route::post('projects/{project}/indirect-costs/sync', [IndirectCostController::class, 'sync']);
    Route::post('projects/{project}/indirect-costs/actuals/sync', [IndirectCostController::class, 'syncActuals']);
    Route::post('projects/{project}/indirect-costs/toggle-lock', [IndirectCostController::class, 'toggleLock']);
    Route::post('projects/{project}/tasks/sync', [ProjectTaskController::class, 'sync']);

    // Project Payments
    Route::apiResource('payments', PaymentController::class);
    Route::post('projects/{project}/payments/sync', [PaymentController::class, 'sync']);

    // Project Issues
    Route::apiResource('issues', ProjectIssueController::class);

    // Project Documentation
    Route::apiResource('documentations', ProjectDocumentationController::class)->only(['store', 'update', 'destroy']);
    Route::delete('documentation-files/{file}', [ProjectDocumentationController::class, 'deleteFile']);

    // New Master Data APIs
    Route::apiResource('users', UserController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('subkons', SubkonController::class);
    Route::apiResource('bowheers', BowheerController::class);
    Route::apiResource('miscs', MiscController::class);
});
