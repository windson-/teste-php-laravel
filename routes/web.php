<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrawlerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [CrawlerController::class, 'index'])->name('index');

Route::get('/run', [CrawlerController::class, 'run'])->name('run');

Route::post('/add', [CrawlerController::class, 'add'])->name('add');
