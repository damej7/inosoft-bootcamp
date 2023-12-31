<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controller\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('task')->group(function () {
    Route::get('/show_tasks', [TaskController::class, 'showTasks']);
    Route::post('/create_task', [TaskController::class, 'createTask']);
    Route::post('/update_task', [TaskController::class, 'updateTask']);

    // NOTE: lanjutkan tugas assignment di routing baru dibawah ini
    Route::delete('/delete_task', [TaskController::class, 'deleteTask']);
    Route::patch('/assign_task', [TaskController::class, 'assignTask']);
    Route::patch('/unassign_task', [TaskController::class, 'unassignTask']);
    Route::post('/create_subtask', [TaskController::class, 'createSubtask']);
    Route::delete('/delete_subtask', [TaskController::class, 'deleteSubtask']);
});
