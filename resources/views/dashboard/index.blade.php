@extends('layouts.app')

@section('title', 'Dashboard - SGPD')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-0 text-primary">Dashboard SGPD</h1>
                            <p class="text-muted mb-0">Panel de control ejecutivo</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary" id="refreshDashboard">
                                <i class="bi bi-arrow-clockwise"></i> Actualizar
                            </button>
                            <button class="btn btn-primary" onclick="exportDashboard()">
                                <i class="bi bi-download"></i> Exportar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs Cards -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Actividades
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($kpis['processing_activities']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard-data fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                DSAR Abiertos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $kpis['dsar_requests']['open'] }}
                            </div>
                            <div class="text-xs text-danger mt-1">
                                @if($kpis['dsar_requests']['overdue'] > 0)
                                    {{ $kpis['dsar_requests']['overdue'] }} vencidos
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-envelope-exclamation fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Riesgos Altos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $kpis['risks']['HIGH'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Auditorías en Curso
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $kpis['audits'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Capacitaciones Pendientes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $kpis['trainings'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-mortarboard fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Performance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $performance['dsar_resolution_rate'] }}%
                            </div>
                            <div class="text-xs text-muted">Resolución DSAR</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Alerts Row -->
    <div class="row">
        <!-- Left Column: Charts -->
        <div class="col-lg-8 mb-4">
            <!-- DSAR Trend Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Tendencia DSAR (6 meses)</h6>
                    <select class="form-select form-select-sm w-auto" id="chartPeriod">
                        <option value="6">6 meses</option>
                        <option value="3">3 meses</option>
                        <option value="12">1 año</option>
                    </select>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="dsarTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Risk Distribution -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-danger">Distribución de Riesgos</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="riskDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-info">Estado de Auditorías</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="auditStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Alerts and Activity -->
        <div class="col-lg-4 mb-4">
            <!-- Alerts -->
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="bi bi-bell-fill me-2"></i>Alertas
                    </h6>
                    <span class="badge bg-warning">{{ count($alerts) }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($alerts as $alert)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    <i class="bi bi-exclamation-circle text-{{ $alert->priority }} me-2"></i>
                                    {{ $alert->title }}
                                </h6>
                                <small class="text-muted">{{ $alert->due_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 small text-muted">{{ $alert->type }}</p>
                        </div>
                        @empty
                        <div class="list-group-item text-center text-muted py-4">
                            <i class="bi bi-check-circle display-6 text-success mb-3"></i>
                            <p class="mb-0">No hay alertas pendientes</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clock-history me-2"></i>Actividad Reciente
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentActivity as $activity)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $activity->name }}</h6>
                                    <p class="mb-1 small text-muted">{{ $activity->type }}</p>
                                </div>
                                <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="list-group-item text-center text-muted py-4">
                            <p class="mb-0">No hay actividad reciente</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Indicators -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Indicadores de Performance</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <div class="bg-primary rounded-circle p-3 me-3">
                                            <i class="bi bi-envelope-check text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0">{{ $performance['dsar_resolution_rate'] }}%</h3>
                                        </div>
                                    </div>
                                    <p class="text-muted mb-0">Tasa Resolución DSAR</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <div class="bg-success rounded-circle p-3 me-3">
                                            <i class="bi bi-clipboard-check text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0">{{ $performance['audit_completion_rate'] }}%</h3>
                                        </div>
                                    </div>
                                    <p class="text-muted mb-0">Completitud Auditorías</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <div class="bg-info rounded-circle p-3 me-3">
                                            <i class="bi bi-mortarboard text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0">{{ $performance['training_completion_rate'] }}%</h3>
                                        </div>
                                    </div>
                                    <p class="text-muted mb-0">Completitud Capacitaciones</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                        <div class="bg-warning rounded-circle p-3 me-3">
                                            <i class="bi bi-shield-check text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="mb-0">{{ $performance['risk_coverage'] }}%</h3>
                                        </div>
                                    </div>
                                    <p class="text-muted mb-0">Cobertura de Riesgos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Alert Details -->
<div class="modal fade" id="alertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalle de Alerta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="alertDetails">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .border-left-success { border-left: 4px solid #1cc88a !important; }
    .border-left-info { border-left: 4px solid #36b9cc !important; }
    .border-left-warning { border-left: 4px solid #f6c23e !important; }
    .border-left-danger { border-left: 4px solid #e74a3b !important; }
    .border-left-secondary { border-left: 4px solid #858796 !important; }
    .chart-area { position: relative; height: 300px; width: 100%; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart Colors based on image colors
    const chartColors = {
        primary: '#4e73df',
        success: '#1cc88a',
        info: '#36b9cc',
        warning: '#f6c23e',
        danger: '#e74a3b',
        secondary: '#858796'
    };

    // Initialize DSAR Trend Chart
    const dsarCtx = document.getElementById('dsarTrendChart').getContext('2d');
    const dsarChart = new Chart(dsarCtx, {
        type: 'line',
        data: {
            labels: @json($charts['dsar_trend']->pluck('month')->map(fn($m) => new Date($m).toLocaleDateString('es-ES', {month: 'short', year: 'numeric'}))),
            datasets: [{
                label: 'DSARs',
                data: @json($charts['dsar_trend']->pluck('count')),
                borderColor: chartColors.primary,
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
                legend: { display: false }
            }
        }
    });

    // Initialize Risk Distribution Chart
    const riskCtx = document.getElementById('riskDistributionChart').getContext('2d');
    const riskChart = new Chart(riskCtx, {
        type: 'doughnut',
        data: {
            labels: @json($charts['risk_distribution']->pluck('status')),
            datasets: [{
                data: @json($charts['risk_distribution']->pluck('count')),
                backgroundColor: [
                    chartColors.danger,
                    chartColors.warning,
                    chartColors.primary,
                    chartColors.info
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Initialize Audit Status Chart
    const auditCtx = document.getElementById('auditStatusChart').getContext('2d');
    const auditChart = new Chart(auditCtx, {
        type: 'bar',
        data: {
            labels: @json($charts['audit_status']->pluck('status')),
            datasets: [{
                label: 'Auditorías',
                data: @json($charts['audit_status']->pluck('count')),
                backgroundColor: chartColors.info,
                borderColor: chartColors.info,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Dashboard Refresh
    document.getElementById('refreshDashboard').addEventListener('click', function() {
        const btn = this;
        btn.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Actualizando...';
        btn.disabled = true;

        // Refresh KPIs
        fetch('{{ route("dashboard.api.kpis") }}')
            .then(response => response.json())
            .then(data => {
                // Update KPI cards
                document.querySelector('[data-kpi="processing_activities"]').textContent = 
                    data.processing_activities.toLocaleString();
                document.querySelector('[data-kpi="dsar_open"]').textContent = 
                    data.dsar_requests.open;
                // ... update other KPIs
            })
            .finally(() => {
                btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Actualizar';
                btn.disabled = false;
            });
    });

    // Alert Details
    function showAlertDetails(alertId, type) {
        fetch(`/api/alerts/${alertId}?type=${type}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('alertDetails').innerHTML = `
                    <h6>${data.title}</h6>
                    <p><strong>Tipo:</strong> ${data.type}</p>
                    <p><strong>Vencimiento:</strong> ${new Date(data.due_at).toLocaleString()}</p>
                    <p><strong>Prioridad:</strong> <span class="badge bg-${data.priority}">${data.priority.toUpperCase()}</span></p>
                    <hr>
                    <p>${data.description || 'Sin descripción adicional'}</p>
                `;
                new bootstrap.Modal(document.getElementById('alertModal')).show();
            });
    }

    // Export Dashboard
    function exportDashboard() {
        window.print();
    }

    // Auto-refresh every 5 minutes
    setInterval(() => {
        if (!document.hidden) {
            location.reload();
        }
    }, 300000);
</script>
@endpush