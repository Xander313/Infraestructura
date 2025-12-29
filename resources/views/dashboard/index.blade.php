@extends('layouts.app')

@section('title', 'Dashboard - SGPD')
@section('active_key', 'dashboard')
@section('h1', 'Dashboard Ejecutivo')
@section('subtitle', 'Panel de control y métricas del sistema')

@section('content')
<div class="space-y-6">
    
    <!-- Header con acciones -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Dashboard SGPD</h2>
            <p class="text-gray-600">Panel de control ejecutivo</p>
        </div>
        <div class="flex gap-2">
            <button onclick="location.reload()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Actualizar
            </button>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar
            </button>
        </div>
    </div>

    <!-- KPIs Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Actividades -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Actividades</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($kpis['processing_activities'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- DSARs -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">DSAR Abiertos</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $kpis['dsar_requests']['open'] ?? 0 }}</p>
                    @if(($kpis['dsar_requests']['overdue'] ?? 0) > 0)
                    <p class="text-xs text-red-600 mt-1">{{ $kpis['dsar_requests']['overdue'] }} vencidos</p>
                    @endif
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Riesgos -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Riesgos Altos</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $kpis['risks']['HIGH'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Auditorías -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Auditorías</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $kpis['audits'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Capacitaciones -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Capacitaciones</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $kpis['trainings'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v6l9-5M12 20l-9-5"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos y Métricas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Gráficos principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tendencia DSAR -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Tendencia DSAR</h3>
                        <p class="text-sm text-gray-500">Últimos 6 meses</p>
                    </div>
                    <select class="text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="6">6 meses</option>
                        <option value="3">3 meses</option>
                        <option value="12">1 año</option>
                    </select>
                </div>
                <div class="h-64">
                    <canvas id="dsarTrendChart"></canvas>
                </div>
            </div>

            <!-- Mini gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Distribución de Riesgos -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Distribución de Riesgos</h3>
                    <div class="h-48">
                        <canvas id="riskDistributionChart"></canvas>
                    </div>
                </div>

                <!-- Estado de Auditorías -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Estado de Auditorías</h3>
                    <div class="h-48">
                        <canvas id="auditStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar - Alertas y Actividad -->
        <div class="space-y-6">
            <!-- Alertas -->
            <div class="bg-white rounded-xl border border-yellow-200 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Alertas</h3>
                            <p class="text-sm text-gray-500">Vencimientos pendientes</p>
                        </div>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ count($alerts) }}
                    </span>
                </div>
                
                <div class="space-y-3">
                    @forelse($alerts as $alert)
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200 hover:bg-white transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start gap-2">
                                <div class="mt-0.5">
                                    @if($alert->priority === 'high')
                                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                    @elseif($alert->priority === 'medium')
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                    @else
                                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $alert->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $alert->type }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($alert->due_at)->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500">No hay alertas pendientes</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Actividad Reciente -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Actividad Reciente</h3>
                            <p class="text-sm text-gray-500">Últimas actividades del sistema</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    @forelse($recentActivity as $activity)
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            @if($activity->type === 'Actividad de Tratamiento')
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            @elseif($activity->type === 'DSAR')
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @else
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $activity->name }}</p>
                            <p class="text-xs text-gray-500">{{ $activity->type }}</p>
                        </div>
                        <span class="text-xs text-gray-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500">No hay actividad reciente</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Indicadores de Performance -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Indicadores de Performance</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Resolución DSAR -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 bg-blue-200 text-blue-800 rounded-full">DSAR</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $performance['dsar_resolution_rate'] ?? 0 }}%</p>
                <p class="text-sm text-gray-600 mt-1">Tasa de Resolución</p>
            </div>

            <!-- Completitud Auditorías -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 bg-green-200 text-green-800 rounded-full">Auditorías</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $performance['audit_completion_rate'] ?? 0 }}%</p>
                <p class="text-sm text-gray-600 mt-1">Completitud</p>
            </div>

            <!-- Completitud Capacitaciones -->
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 bg-indigo-200 text-indigo-800 rounded-full">Capacitación</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $performance['training_completion_rate'] ?? 0 }}%</p>
                <p class="text-sm text-gray-600 mt-1">Completitud</p>
            </div>

            <!-- Cobertura de Riesgos -->
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-amber-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium px-2 py-1 bg-amber-200 text-amber-800 rounded-full">Riesgos</span>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $performance['risk_coverage'] ?? 0 }}%</p>
                <p class="text-sm text-gray-600 mt-1">Cobertura DPIA</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Colors matching your layout
    const colors = {
        primary: '#4e73df',
        success: '#1cc88a',
        info: '#36b9cc',
        warning: '#f6c23e',
        danger: '#e74a3b'
    };

    // Helper para formatear fechas
    function formatMonth(dateStr) {
        try {
            const date = new Date(dateStr);
            return date.toLocaleDateString('es-ES', {month: 'short', year: 'numeric'});
        } catch(e) {
            return dateStr;
        }
    }

    // DSAR Trend Chart
    const dsarCtx = document.getElementById('dsarTrendChart');
    if (dsarCtx) {
        const dsarMonths = {!! json_encode($charts['dsar_trend']->pluck('month')->toArray()) !!};
        const dsarCounts = {!! json_encode($charts['dsar_trend']->pluck('count')->toArray()) !!};
        const dsarLabels = dsarMonths.map(m => formatMonth(m));
        
        new Chart(dsarCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: dsarLabels,
                datasets: [{
                    label: 'DSARs',
                    data: dsarCounts,
                    borderColor: colors.primary,
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return Number.isInteger(value) ? value : '';
                            }
                        }
                    }
                }
            }
        });
    }

    // Risk Distribution Chart
    const riskCtx = document.getElementById('riskDistributionChart');
    if (riskCtx) {
        const riskLabels = {!! json_encode($charts['risk_distribution']->pluck('status')->toArray()) !!};
        const riskData = {!! json_encode($charts['risk_distribution']->pluck('count')->toArray()) !!};
        
        new Chart(riskCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: riskLabels,
                datasets: [{
                    data: riskData,
                    backgroundColor: [colors.danger, colors.warning, colors.primary, colors.info],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }

    // Audit Status Chart
    const auditCtx = document.getElementById('auditStatusChart');
    if (auditCtx) {
        const auditLabels = {!! json_encode($charts['audit_status']->pluck('status')->toArray()) !!};
        const auditData = {!! json_encode($charts['audit_status']->pluck('count')->toArray()) !!};
        
        new Chart(auditCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: auditLabels,
                datasets: [{
                    label: 'Auditorías',
                    data: auditData,
                    backgroundColor: colors.info,
                    borderColor: colors.info,
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { 
                            stepSize: 1,
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    // ============================================
    // AUTO-REFRESH SYSTEM
    // ============================================
    
    let autoRefreshInterval;

    function startAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
        
        autoRefreshInterval = setInterval(() => {
            refreshDashboardData();
        }, 30000); // 30 segundos
    }

    async function refreshDashboardData() {
        try {
            document.body.classList.add('refreshing');
            
            const [kpisResponse, alertsResponse, activityResponse] = await Promise.all([
                fetch('{{ route("dashboard.api.kpis") }}'),
                fetch('{{ route("dashboard.api.alerts") }}'),
                fetch('{{ route("dashboard.api.activity") }}')
            ]);
            
            const kpis = await kpisResponse.json();
            const alerts = await alertsResponse.json();
            const activity = await activityResponse.json();
            
            // Actualizar KPIs en la UI (necesitas IDs en tus elementos)
            updateKPIValue('.kpi-actividades', kpis.processing_activities);
            updateKPIValue('.kpi-dsar-open', kpis.dsar_requests.open);
            updateKPIValue('.kpi-dsar-overdue', kpis.dsar_requests.overdue);
            updateKPIValue('.kpi-riesgos-high', kpis.risks.HIGH || 0);
            updateKPIValue('.kpi-auditorias', kpis.audits);
            updateKPIValue('.kpi-capacitaciones', kpis.trainings);
            
            // Actualizar notificación count
            if (window.sgpdLayout && window.sgpdLayout.notificationCount !== undefined) {
                window.sgpdLayout.notificationCount = alerts.length;
            }
            
            showUpdateNotification('Dashboard actualizado');
            
        } catch (error) {
            console.error('Error refreshing dashboard:', error);
        } finally {
            document.body.classList.remove('refreshing');
        }
    }

    function updateKPIValue(selector, value) {
        const element = document.querySelector(selector);
        if (element) {
            const current = parseInt(element.textContent.replace(/,/g, ''));
            if (!isNaN(current) && current !== value) {
                animateCount(element, current, value);
            }
        }
    }

    function animateCount(element, start, end) {
        const duration = 500;
        const startTime = performance.now();
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const currentValue = Math.floor(start + (end - start) * progress);
            element.textContent = currentValue.toLocaleString();
            
            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }
        
        requestAnimationFrame(update);
    }

    function showUpdateNotification(message) {
        // Crear notificación toast
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transform translate-y-full opacity-0 transition-all duration-300 z-50';
        toast.textContent = `🔄 ${message}`;
        document.body.appendChild(toast);
        
        // Animar entrada
        setTimeout(() => {
            toast.classList.remove('translate-y-full', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        }, 10);
        
        // Auto-remover
        setTimeout(() => {
            toast.classList.add('translate-y-full', 'opacity-0');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    function setupTabSync() {
        if (typeof BroadcastChannel !== 'undefined') {
            const channel = new BroadcastChannel('dashboard-updates');
            
            channel.addEventListener('message', (event) => {
                if (event.data === 'dashboard-updated') {
                    refreshDashboardData();
                    showUpdateNotification('Cambios detectados - Actualizando...');
                }
            });
            
            window.dashboardNotifyUpdate = function() {
                channel.postMessage('dashboard-updated');
                refreshDashboardData();
            };
        }
    }

    // Inicializar
    document.addEventListener('DOMContentLoaded', () => {
        startAutoRefresh();
        setupTabSync();
        
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                refreshDashboardData();
            }
        });
        
        window.addEventListener('online', refreshDashboardData);
    });

    // Estilos para el indicador de refresco
    const style = document.createElement('style');
    style.textContent = `
        .refreshing::after {
            content: '';
            position: fixed;
            top: 0;
            right: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(to bottom, #4f46e5, #7c3aed);
            z-index: 9999;
            animation: refresh-pulse 30s linear infinite;
        }
        
        @keyframes refresh-pulse {
            0% { height: 0%; opacity: 0.7; }
            50% { height: 100%; opacity: 0.3; }
            100% { height: 0%; opacity: 0.7; }
        }
        
        .kpi-updated {
            animation: kpi-pulse 1s ease-in-out;
        }
        
        @keyframes kpi-pulse {
            0% { background-color: transparent; }
            50% { background-color: rgba(34, 197, 94, 0.1); }
            100% { background-color: transparent; }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
@endsection