@extends('layouts.app')

@section('title', 'Dashboard - SGPD')
@section('active_key', 'dashboard')
@section('h1', 'Dashboard Ejecutivo')
@section('subtitle', 'Panel de control y métricas del sistema')

@section('content')
<div class="space-y-6">
    
    <!-- Header con acciones mejorado -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <h2 class="text-2xl font-bold text-gray-900 bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                    Dashboard SGPD
                </h2>
                <span class="px-2 py-1 text-xs font-semibold bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-full">
                    COAC
                </span>
            </div>
            <p class="text-sm text-gray-600 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Panel de control ejecutivo en tiempo real
            </p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" 
                    class="px-4 py-2 border border-gray-300 rounded-xl hover:bg-gray-50 flex items-center gap-2 transition-all duration-200 hover:scale-[1.02] active:scale-95 shadow-sm hover:shadow">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar
            </button>
            <button id="refreshDashboard" 
                    class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl flex items-center gap-2 transition-all duration-200 hover:scale-[1.02] active:scale-95 hover:shadow-lg hover:from-blue-600 hover:to-blue-700">
                <svg id="refreshIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Actualizar</span>
            </button>
        </div>
    </div>

    <!-- KPIs Grid mejorado -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        <!-- Actividades - Tarjeta mejorada -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-xl transition-all duration-300 hover:border-blue-200 hover:-translate-y-1 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Actividades</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($kpis['processing_activities'] ?? 0) }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-blue-600 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                </svg>
                Registros activos
            </div>
        </div>

        <!-- DSARs - Tarjeta mejorada -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-xl transition-all duration-300 hover:border-yellow-200 hover:-translate-y-1 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">DSARs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $kpis['dsar_requests']['open'] ?? 0 }}</p>
                    @if(($kpis['dsar_requests']['overdue'] ?? 0) > 0)
                    <div class="flex items-center gap-2 mt-2">
                        <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                        <p class="text-xs font-semibold text-red-600">{{ $kpis['dsar_requests']['overdue'] }} vencidos</p>
                    </div>
                    @endif
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-yellow-600 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Solicitudes abiertas
            </div>
        </div>

        <!-- Riesgos - Tarjeta mejorada -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-xl transition-all duration-300 hover:border-red-200 hover:-translate-y-1 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Riesgos Altos</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $kpis['risks']['HIGH'] ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-red-50 to-red-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-red-600 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Requieren atención
            </div>
        </div>

        <!-- Auditorías - Tarjeta mejorada -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-xl transition-all duration-300 hover:border-indigo-200 hover:-translate-y-1 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Auditorías</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $kpis['audits'] ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-indigo-600 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                En progreso
            </div>
        </div>

        <!-- Capacitaciones - Tarjeta mejorada -->
        <div class="group bg-white rounded-2xl border border-gray-200 p-6 shadow-sm hover:shadow-xl transition-all duration-300 hover:border-green-200 hover:-translate-y-1 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm text-gray-500 font-medium mb-1">Capacitaciones</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $kpis['trainings'] ?? 0 }}</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-green-50 to-green-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v6l9-5M12 20l-9-5"/>
                    </svg>
                </div>
            </div>
            <div class="text-xs text-green-600 font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Pendientes
            </div>
        </div>
    </div>

    <!-- Gráficos y Métricas Mejoradas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Gráficos principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tendencia DSAR - Mejorado -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <span class="w-3 h-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full"></span>
                            Tendencia DSAR
                        </h3>
                        <p class="text-sm text-gray-500">Últimos 6 meses • Actividad por mes</p>
                    </div>
                    <div class="flex gap-2">
                        <select id="trendPeriod" class="text-sm border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 px-3 py-2 bg-gray-50">
                            <option value="6">6 meses</option>
                            <option value="3">3 meses</option>
                            <option value="12">1 año</option>
                        </select>
                        <button onclick="downloadChart('dsarTrendChart', 'tendencia-dsar.png')" 
                                class="p-2 hover:bg-gray-100 rounded-xl border border-gray-200 transition-colors">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="dsarTrendChart"></canvas>
                </div>
            </div>

            <!-- Mini gráficos mejorados -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Distribución de Riesgos - Mejorado -->
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <span class="w-3 h-3 bg-gradient-to-r from-red-500 to-red-600 rounded-full"></span>
                                Distribución de Riesgos
                            </h3>
                            <p class="text-sm text-gray-500">Por nivel de severidad</p>
                        </div>
                        <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                            Total: {{ array_sum($kpis['risks'] ?? []) }}
                        </div>
                    </div>
                    <div class="h-56">
                        <canvas id="riskDistributionChart"></canvas>
                    </div>
                </div>

                <!-- Estado de Auditorías - Mejorado -->
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <span class="w-3 h-3 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full"></span>
                                Estado de Auditorías
                            </h3>
                            <p class="text-sm text-gray-500">Por estado de avance</p>
                        </div>
                        <div class="flex gap-1">
                            @foreach(['PLANNED' => 'indigo', 'IN_PROGRESS' => 'blue', 'COMPLETED' => 'green'] as $status => $color)
                            <div class="w-2 h-2 bg-{{ $color }}-500 rounded-full"></div>
                            @endforeach
                        </div>
                    </div>
                    <div class="h-56">
                        <canvas id="auditStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar - Alertas y Actividad Mejorada -->
        <div class="space-y-6">
            <!-- Alertas - Tarjeta mejorada -->
            <div class="bg-gradient-to-br from-yellow-50 via-white to-yellow-50 rounded-2xl border border-yellow-200 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Alertas</h3>
                            <p class="text-sm text-gray-500">Vencimientos pendientes</p>
                        </div>
                    </div>
                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                        {{ count($alerts) }}
                    </span>
                </div>
                
                <div class="space-y-3">
                    @forelse($alerts as $alert)
                    <div class="group p-4 bg-white rounded-xl border border-gray-200 hover:bg-yellow-50 hover:border-yellow-300 transition-all duration-200 cursor-pointer hover:shadow-sm">
                        <div class="flex justify-between items-start">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5">
                                    @if($alert->priority === 'high')
                                        <div class="w-3 h-3 bg-gradient-to-r from-red-500 to-red-600 rounded-full animate-pulse"></div>
                                    @elseif($alert->priority === 'medium')
                                        <div class="w-3 h-3 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-full"></div>
                                    @else
                                        <div class="w-3 h-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full"></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 group-hover:text-yellow-900">{{ $alert->title }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-800 rounded-full">
                                            {{ $alert->type }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($alert->due_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button class="text-gray-300 hover:text-gray-500 group-hover:text-yellow-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">¡Excelente!</p>
                        <p class="text-xs text-gray-500 mt-1">No hay alertas pendientes</p>
                    </div>
                    @endforelse
                </div>
                
                @if(count($alerts) > 0)
                <div class="mt-6 pt-4 border-t border-yellow-100">
                    <button class="w-full text-center text-sm text-yellow-700 hover:text-yellow-800 font-medium py-2 rounded-lg hover:bg-yellow-100 transition-colors">
                        Ver todas las alertas →
                    </button>
                </div>
                @endif
            </div>

            <!-- Actividad Reciente - Tarjeta mejorada -->
            <div class="bg-gradient-to-br from-blue-50 via-white to-blue-50 rounded-2xl border border-blue-200 p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="flex items-start gap-3 group hover:bg-blue-50 p-3 rounded-xl transition-colors">
                        <div class="flex-shrink-0">
                            @if($activity->type === 'Actividad de Tratamiento')
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            @elseif($activity->type === 'DSAR')
                            <div class="w-10 h-10 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @else
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-200 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $activity->name }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-800 rounded-full">
                                    {{ $activity->type }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-500 mt-1 opacity-0 group-hover:opacity-100 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Sin actividad reciente</p>
                        <p class="text-xs text-gray-500 mt-1">Se mostrará aquí la actividad nueva</p>
                    </div>
                    @endforelse
                </div>
                
                @if(count($recentActivity) > 0)
                <div class="mt-6 pt-4 border-t border-blue-100">
                    <button class="w-full text-center text-sm text-blue-700 hover:text-blue-800 font-medium py-2 rounded-lg hover:bg-blue-100 transition-colors">
                        Ver historial completo →
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Indicadores de Performance Mejorados -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 p-8 shadow-sm">
        <div class="flex justify-between items-center mb-8">
            <div class="space-y-1">
                <h3 class="text-lg font-semibold text-gray-900">Indicadores de Performance</h3>
                <p class="text-sm text-gray-600">Métricas clave de cumplimiento y eficiencia</p>
            </div>
            <div class="text-xs text-gray-500 bg-white px-3 py-1 rounded-full border border-blue-100">
                Actualizado ahora
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Resolución DSAR - Mejorado -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 bg-blue-50 text-blue-700 rounded-full border border-blue-100">DSAR</span>
                </div>
                <div class="mb-3">
                    <div class="flex items-end gap-1">
                        <p class="text-3xl font-bold text-gray-900">{{ $performance['dsar_resolution_rate'] ?? 0 }}%</p>
                        <div class="flex items-center mb-1">
                            @if(($performance['dsar_resolution_rate'] ?? 0) >= 80)
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                            </svg>
                            @else
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd"/>
                            </svg>
                            @endif
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Tasa de Resolución</p>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full" 
                         style="width: {{ min($performance['dsar_resolution_rate'] ?? 0, 100) }}%"></div>
                </div>
            </div>

            <!-- Completitud Auditorías - Mejorado -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 bg-green-50 text-green-700 rounded-full border border-green-100">Auditorías</span>
                </div>
                <div class="mb-3">
                    <div class="flex items-end gap-1">
                        <p class="text-3xl font-bold text-gray-900">{{ $performance['audit_completion_rate'] ?? 0 }}%</p>
                        <div class="flex items-center mb-1">
                            @if(($performance['audit_completion_rate'] ?? 0) >= 75)
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                            </svg>
                            @else
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd"/>
                            </svg>
                            @endif
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Completitud</p>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-green-500 to-green-600 rounded-full" 
                         style="width: {{ min($performance['audit_completion_rate'] ?? 0, 100) }}%"></div>
                </div>
            </div>

            <!-- Completitud Capacitaciones - Mejorado -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full border border-indigo-100">Capacitación</span>
                </div>
                <div class="mb-3">
                    <div class="flex items-end gap-1">
                        <p class="text-3xl font-bold text-gray-900">{{ $performance['training_completion_rate'] ?? 0 }}%</p>
                        <div class="flex items-center mb-1">
                            @if(($performance['training_completion_rate'] ?? 0) >= 90)
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                            </svg>
                            @else
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd"/>
                            </svg>
                            @endif
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Completitud</p>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full" 
                         style="width: {{ min($performance['training_completion_rate'] ?? 0, 100) }}%"></div>
                </div>
            </div>

            <!-- Cobertura de Riesgos - Mejorado -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 bg-amber-50 text-amber-700 rounded-full border border-amber-100">Riesgos</span>
                </div>
                <div class="mb-3">
                    <div class="flex items-end gap-1">
                        <p class="text-3xl font-bold text-gray-900">{{ $performance['risk_coverage'] ?? 0 }}%</p>
                        <div class="flex items-center mb-1">
                            @if(($performance['risk_coverage'] ?? 0) >= 60)
                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                            </svg>
                            @else
                            <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd"/>
                            </svg>
                            @endif
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Cobertura DPIA</p>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-amber-500 to-orange-600 rounded-full" 
                         style="width: {{ min($performance['risk_coverage'] ?? 0, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .chart-tooltip {
        background: white !important;
        border-radius: 8px !important;
        border: 1px solid #e5e7eb !important;
        padding: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
    }
    
    .chart-tooltip .title {
        font-weight: 600 !important;
        color: #374151 !important;
        margin-bottom: 4px !important;
    }
    
    .chart-tooltip .value {
        color: #1f2937 !important;
        font-size: 14px !important;
    }
    
    /* Animaciones personalizadas */
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    .floating {
        animation: float 3s ease-in-out infinite;
    }
    
    /* Efectos de hover mejorados */
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    }
    
    /* Gradientes de fondo */
    .gradient-border {
        position: relative;
        background: white;
        border-radius: 16px;
    }
    
    .gradient-border::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, #3b82f6, #8b5cf6, #ec4899);
        border-radius: 18px;
        z-index: -1;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gradient-border:hover::before {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
<script>
    // Configuración global de Chart.js
    Chart.defaults.font.family = "'Montserrat', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial";
    Chart.defaults.color = '#6b7280';
    
    // Colores actualizados
    const colors = {
        primary: { gradient: ['#3b82f6', '#2563eb'], solid: '#3b82f6' },
        success: { gradient: ['#10b981', '#059669'], solid: '#10b981' },
        info: { gradient: ['#06b6d4', '#0891b2'], solid: '#06b6d4' },
        warning: { gradient: ['#f59e0b', '#d97706'], solid: '#f59e0b' },
        danger: { gradient: ['#ef4444', '#dc2626'], solid: '#ef4444' },
        purple: { gradient: ['#8b5cf6', '#7c3aed'], solid: '#8b5cf6' }
    };

    // Helper para crear gradientes
    function createGradient(ctx, colorStops, vertical = false) {
        const gradient = vertical 
            ? ctx.createLinearGradient(0, 0, 0, 400)
            : ctx.createLinearGradient(0, 0, 400, 0);
        
        colorStops.forEach((stop, i) => {
            gradient.addColorStop(i / (colorStops.length - 1), stop);
        });
        
        return gradient;
    }

    // Formatear meses
    function formatMonth(dateStr) {
        try {
            const date = new Date(dateStr);
            return date.toLocaleDateString('es-ES', {month: 'short', year: 'numeric'});
        } catch(e) {
            return dateStr;
        }
    }

    // Notificación elegante para actualización
    function showUpdateNotification(message, type = 'success') {
        // Crear toast
        const toast = document.createElement('div');
        const icons = {
            success: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`,
            info: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>`,
            warning: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>`
        };
        
        const bgColors = {
            success: 'bg-gradient-to-r from-green-500 to-emerald-600',
            info: 'bg-gradient-to-r from-blue-500 to-blue-600',
            warning: 'bg-gradient-to-r from-amber-500 to-orange-600'
        };
        
        toast.className = `fixed top-4 right-4 ${bgColors[type]} text-white px-6 py-4 rounded-2xl shadow-2xl transform transition-all duration-500 z-[9999] flex items-center gap-3 max-w-md`;
        toast.style.transform = 'translateX(400px)';
        toast.innerHTML = `
            <div class="flex-shrink-0">${icons[type]}</div>
            <div class="flex-1">
                <p class="font-semibold">${message}</p>
                <p class="text-sm opacity-90 mt-1">${new Date().toLocaleTimeString('es-ES', {hour: '2-digit', minute:'2-digit'})}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-white/70 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        
        document.body.appendChild(toast);
        
        // Animar entrada
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 10);
        
        // Auto-remover después de 4 segundos
        setTimeout(() => {
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => {
                if (toast.parentElement) {
                    document.body.removeChild(toast);
                }
            }, 500);
        }, 4000);
    }

    // Descargar gráfico como imagen
    function downloadChart(canvasId, filename) {
        const canvas = document.getElementById(canvasId);
        const link = document.createElement('a');
        link.download = filename;
        link.href = canvas.toDataURL('image/png');
        link.click();
        showUpdateNotification('Gráfico descargado correctamente', 'success');
    }

    // Gráfico de tendencia DSAR mejorado
    let dsarChart;
    const dsarCtx = document.getElementById('dsarTrendChart');
    if (dsarCtx) {
        const dsarMonths = {!! json_encode($charts['dsar_trend']->pluck('month')->toArray()) !!};
        const dsarCounts = {!! json_encode($charts['dsar_trend']->pluck('count')->toArray()) !!};
        const dsarLabels = dsarMonths.map(m => formatMonth(m));
        
        const gradient = createGradient(dsarCtx.getContext('2d'), ['rgba(59, 130, 246, 0.3)', 'rgba(59, 130, 246, 0.1)'], true);
        
        dsarChart = new Chart(dsarCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: dsarLabels,
                datasets: [{
                    label: 'DSARs',
                    data: dsarCounts,
                    borderColor: colors.primary.solid,
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary.solid,
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'white',
                        titleColor: '#1f2937',
                        bodyColor: '#4b5563',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        cornerRadius: 8,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} solicitudes`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return Number.isInteger(value) ? value : '';
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }

    // Gráfico de distribución de riesgos mejorado
    let riskChart;
    const riskCtx = document.getElementById('riskDistributionChart');
    if (riskCtx) {
        const riskLabels = {!! json_encode($charts['risk_distribution']->pluck('status')->toArray()) !!};
        const riskData = {!! json_encode($charts['risk_distribution']->pluck('count')->toArray()) !!};
        
        const backgroundColors = [
            'rgba(239, 68, 68, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(139, 92, 246, 0.8)'
        ];
        
        const hoverColors = [
            'rgb(239, 68, 68)',
            'rgb(245, 158, 11)',
            'rgb(59, 130, 246)',
            'rgb(139, 92, 246)'
        ];
        
        riskChart = new Chart(riskCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: riskLabels,
                datasets: [{
                    data: riskData,
                    backgroundColor: backgroundColors,
                    hoverBackgroundColor: hoverColors,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { 
                    legend: { 
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.parsed / total) * 100);
                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfico de estado de auditorías mejorado
    let auditChart;
    const auditCtx = document.getElementById('auditStatusChart');
    if (auditCtx) {
        const auditLabels = {!! json_encode($charts['audit_status']->pluck('status')->toArray()) !!};
        const auditData = {!! json_encode($charts['audit_status']->pluck('count')->toArray()) !!};
        
        const gradient = createGradient(auditCtx.getContext('2d'), ['#06b6d4', '#0891b2']);
        
        auditChart = new Chart(auditCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: auditLabels,
                datasets: [{
                    label: 'Auditorías',
                    data: auditData,
                    backgroundColor: gradient,
                    borderColor: colors.info.solid,
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} auditorías`;
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: { 
                            stepSize: 1,
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // ============================================
    // SISTEMA DE ACTUALIZACIÓN MEJORADO
    // ============================================
    
    let autoRefreshInterval;
    let isRefreshing = false;

    // Animación del botón de actualizar
    document.getElementById('refreshDashboard')?.addEventListener('click', async function(e) {
        e.preventDefault();
        await refreshDashboardData(true);
    });

    function startAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
        
        autoRefreshInterval = setInterval(() => {
            refreshDashboardData();
        }, 60000); // 60 segundos
    }

    async function refreshDashboardData(userInitiated = false) {
        if (isRefreshing) return;
        
        isRefreshing = true;
        
        // Animación del botón
        const refreshBtn = document.getElementById('refreshDashboard');
        const refreshIcon = document.getElementById('refreshIcon');
        if (refreshBtn && userInitiated) {
            refreshBtn.disabled = true;
            refreshBtn.classList.add('opacity-75');
            refreshIcon.classList.add('animate-spin');
        }
        
        try {
            // Mostrar indicador de carga sutil
            document.body.classList.add('refreshing');
            
            const [kpisResponse, alertsResponse, activityResponse] = await Promise.all([
                fetch('{{ route("dashboard.api.kpis") }}'),
                fetch('{{ route("dashboard.api.alerts") }}'),
                fetch('{{ route("dashboard.api.activity") }}')
            ]);
            
            const kpis = await kpisResponse.json();
            const alerts = await alertsResponse.json();
            const activity = await activityResponse.json();
            
            // Animar actualización de KPIs
            animateKPIUpdate('.kpi-actividades', kpis.processing_activities);
            animateKPIUpdate('.kpi-dsar-open', kpis.dsar_requests.open);
            animateKPIUpdate('.kpi-riesgos-high', kpis.risks.HIGH || 0);
            animateKPIUpdate('.kpi-auditorias', kpis.audits);
            animateKPIUpdate('.kpi-capacitaciones', kpis.trainings);
            
            // Actualizar notificación count
            if (window.sgpdLayout && window.sgpdLayout.notificationCount !== undefined) {
                window.sgpdLayout.notificationCount = alerts.length;
            }
            
            // Mostrar notificación elegante
            if (userInitiated) {
                showUpdateNotification('Dashboard actualizado correctamente', 'success');
            } else {
                showUpdateNotification('Datos actualizados automáticamente', 'info');
            }
            
        } catch (error) {
            console.error('Error refreshing dashboard:', error);
            if (userInitiated) {
                showUpdateNotification('Error al actualizar los datos', 'warning');
            }
        } finally {
            isRefreshing = false;
            document.body.classList.remove('refreshing');
            
            // Restaurar botón
            if (refreshBtn && userInitiated) {
                refreshBtn.disabled = false;
                refreshBtn.classList.remove('opacity-75');
                refreshIcon.classList.remove('animate-spin');
            }
        }
    }

    function animateKPIUpdate(selector, newValue) {
        const element = document.querySelector(selector);
        if (!element) return;
        
        const current = parseInt(element.textContent.replace(/,/g, ''));
        if (!isNaN(current) && current !== newValue) {
            // Añadir clase de animación
            element.parentElement.parentElement.classList.add('kpi-updated');
            
            // Animar conteo
            animateCount(element, current, newValue);
            
            // Remover clase de animación después
            setTimeout(() => {
                element.parentElement.parentElement.classList.remove('kpi-updated');
            }, 1000);
        }
    }

    function animateCount(element, start, end) {
        const duration = 800;
        const startTime = performance.now();
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function for smooth animation
            const easeOutQuart = 1 - Math.pow(1 - progress, 4);
            const currentValue = Math.floor(start + (end - start) * easeOutQuart);
            element.textContent = currentValue.toLocaleString();
            
            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }
        
        requestAnimationFrame(update);
    }

    // Sincronización entre pestañas
    function setupTabSync() {
        if (typeof BroadcastChannel !== 'undefined') {
            const channel = new BroadcastChannel('sgpd-dashboard-updates');
            
            channel.addEventListener('message', (event) => {
                if (event.data === 'dashboard-updated') {
                    refreshDashboardData();
                    showUpdateNotification('Cambios detectados en otra pestaña', 'info');
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
        
        // Actualizar cuando la pestaña se vuelve visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                refreshDashboardData();
            }
        });
        
        // Actualizar cuando hay conexión
        window.addEventListener('online', refreshDashboardData);
        
        // Añadir clase de hover-lift a tarjetas
        document.querySelectorAll('.group.bg-white.rounded-2xl').forEach(card => {
            card.classList.add('hover-lift');
        });
    });

    // Estilos CSS para animaciones
    const style = document.createElement('style');
    style.textContent = `
        .refreshing::after {
            content: '';
            position: fixed;
            top: 0;
            right: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, #3b82f6, #8b5cf6, #ec4899);
            z-index: 9999;
            animation: refresh-pulse 60s linear infinite;
        }
        
        @keyframes refresh-pulse {
            0% { 
                height: 0%; 
                opacity: 0.8; 
            }
            10% { 
                height: 100%; 
                opacity: 0.4; 
            }
            20% { 
                height: 0%; 
                opacity: 0.8; 
            }
            100% { 
                height: 0%; 
                opacity: 0.8; 
            }
        }
        
        .kpi-updated {
            animation: kpi-highlight 1s ease-in-out;
        }
        
        @keyframes kpi-highlight {
            0% { 
                transform: translateY(0); 
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            50% { 
                transform: translateY(-4px); 
                box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.2), 0 10px 10px -5px rgba(59, 130, 246, 0.1);
                background-color: rgba(59, 130, 246, 0.05);
                border-color: #93c5fd;
            }
            100% { 
                transform: translateY(0); 
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
        }
        
        /* Animación para puntos de alerta */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Smooth transitions */
        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
@endsection