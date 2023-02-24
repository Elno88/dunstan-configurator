<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\KonfiguratorController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Index and initial step
Route::get('/', [KonfiguratorController::class, 'index'])->name('index');
Route::get('/step/{step}', [KonfiguratorController::class, 'step'])->name('step.path');
Route::any('/step/{step}/{function}', [KonfiguratorController::class, 'step'])->name('step.function');
Route::post('/step/{step}', [KonfiguratorController::class, 'validateStep'])->name('step.validate');

// Logviewer
Route::get('/admin/logs', [LogViewerController::class, 'index'])
    ->middleware(['auth']);

require __DIR__.'/auth.php';
