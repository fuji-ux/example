<?php

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

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\StudyLogController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AiReportController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// ログイン
//Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
//Route::post('/login', [LoginController::class, 'login'])->name('login.custom');
//Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ユーザー用ルート
Route::group(['middleware' => ['auth:web']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    Route::resource('study_logs', 'StudyLogController');
    Route::resource('materials', 'MaterialController');

    // AIレポート (プレミアユーザー限定)
    Route::middleware(['premium'])->group(function () {
        Route::get('ai-reports', [AiReportController::class, 'index'])->name('ai-reports.index');
        Route::get('ai-reports/{ai_report}', [AiReportController::class, 'show'])->name('ai-reports.show');
    });
});



// 管理者用ルート
Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['auth:admin']], function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });
});