<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Privacy\ProcessingActivityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Core\OrgController;
use App\Http\Controllers\Privacy\DataSubjectController;

Route::get('/', function () {
    return view('core/extencion');
});

// Dashboard Routes - SIN middleware
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/api/dashboard/kpis', [DashboardController::class, 'apiKPIs'])->name('dashboard.api.kpis');
Route::get('/api/dashboard/alerts', [DashboardController::class, 'apiAlerts'])->name('dashboard.api.alerts');
Route::get('/api/dashboard/activity', [DashboardController::class, 'apiRecentActivity'])->name('dashboard.api.activity');

// RAT Routes
Route::resource('rat', ProcessingActivityController::class);

// Org Routes
Route::get('/org/select/{org}', function ($orgId) {
    session(['org_id' => $orgId]);
    return redirect()->back()->with('success', 'Organización activada.');
})->name('orgs.select');

Route::resource('orgs', OrgController::class);

// Data Subjects Routes (Fase 6) - Rutas completas
Route::resource('data-subjects', DataSubjectController::class);

// Rutas adicionales para consentimientos
Route::get('/data-subjects/{dataSubject}/consent/create', 
    [DataSubjectController::class, 'createConsent'])
    ->name('data-subjects.consent.create');
    
Route::post('/data-subjects/{dataSubject}/consent', 
    [DataSubjectController::class, 'storeConsent'])
    ->name('data-subjects.consent.store');
    
Route::post('/consent/{consent}/revoke', 
    [DataSubjectController::class, 'revokeConsent'])
    ->name('data-subjects.consent.revoke');

// Risk routes
require __DIR__.'/risk.php';

use App\Http\Controllers\Audit\AuditController;
use App\Http\Controllers\Audit\ControlController;
use App\Http\Controllers\Audit\AuditFindingController;
use App\Http\Controllers\Audit\CorrectiveActionController;

Route::prefix('audit')->group(function(){
    Route::resource('audits', AuditController::class);
    Route::resource('controls', ControlController::class);
    Route::resource('findings', AuditFindingController::class);
    Route::resource('corrective_actions', CorrectiveActionController::class);
});