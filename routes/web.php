<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Privacy\ProcessingActivityController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('core/extencion');
});

// Dashboard Routes - SIN middleware
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/api/dashboard/kpis', [DashboardController::class, 'apiKPIs'])->name('dashboard.api.kpis');
Route::get('/api/dashboard/alerts', [DashboardController::class, 'apiAlerts'])->name('dashboard.api.alerts');
Route::get('/api/dashboard/activity', [DashboardController::class, 'apiRecentActivity'])->name('dashboard.api.activity');

Route::resource('rat', ProcessingActivityController::class);


require __DIR__.'/risk.php';
