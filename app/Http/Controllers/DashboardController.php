<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    // Obtener org_id del usuario autenticado
    private function getOrgId()
    {
        // Ajustar según tu modelo de usuario
        return Auth::user()->org_id;
    }

    // Dashboard principal con todos los KPIs
    public function index()
    {
        $orgId = $this->getOrgId();
        
        // Cache por 5 minutos para mejorar performance
        $data = Cache::remember("dashboard_{$orgId}", 300, function () use ($orgId) {
            return [
                'kpis' => $this->getKPIs($orgId),
                'recentActivity' => $this->getRecentActivity($orgId),
                'alerts' => $this->getAlerts($orgId),
                'performance' => $this->getPerformanceIndicators($orgId),
                'charts' => $this->getChartsData($orgId)
            ];
        });

        return view('dashboard.index', $data);
    }

    // KPIs principales
    private function getKPIs($orgId)
    {
        return [
            'processing_activities' => $this->getProcessingActivitiesCount($orgId),
            'dsar_requests' => $this->getDsarRequestsStats($orgId),
            'risks' => $this->getRisksBySeverity($orgId),
            'audits' => $this->getAuditsInProgress($orgId),
            'trainings' => $this->getPendingTrainings($orgId)
        ];
    }

    // 1. #Actividades de Tratamiento
    private function getProcessingActivitiesCount($orgId)
    {
        return DB::table('privacy.processing_activity')
            ->where('org_id', $orgId)
            ->count();
    }

    // 2. DSAR abiertos/vencidos
    private function getDsarRequestsStats($orgId)
    {
        $stats = DB::table('privacy.dsar_request')
            ->where('org_id', $orgId)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'OPEN' THEN 1 ELSE 0 END) as open_count,
                SUM(CASE WHEN status = 'OPEN' AND due_at < NOW() THEN 1 ELSE 0 END) as overdue_count
            ")
            ->first();

        return [
            'total' => $stats->total ?? 0,
            'open' => $stats->open_count ?? 0,
            'overdue' => $stats->overdue_count ?? 0
        ];
    }

    // 3. Riesgos por severidad
    private function getRisksBySeverity($orgId)
    {
        return DB::table('risk.risk')
            ->where('org_id', $orgId)
            ->select('risk_type as severity', DB::raw('COUNT(*) as count'))
            ->groupBy('risk_type')
            ->orderBy('count', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->severity => $item->count];
            })
            ->toArray();
    }

    // 4. Auditorías en curso
    private function getAuditsInProgress($orgId)
    {
        return DB::table('audit.audit')
            ->where('org_id', $orgId)
            ->whereIn('status', ['PLANNED', 'IN_PROGRESS'])
            ->count();
    }

    // 5. Capacitaciones pendientes
    private function getPendingTrainings($orgId)
    {
        return DB::table('privacy.training_assignment as ta')
            ->join('privacy.training_course as tc', 'ta.course_id', '=', 'tc.course_id')
            ->where('tc.org_id', $orgId)
            ->where('ta.status', 'PENDING')
            ->where('ta.due_at', '>', now())
            ->count();
    }

    // Actividad reciente (created_at/updated_at de entidades clave)
    private function getRecentActivity($orgId)
    {
        $activities = collect();

        // Actividades de tratamiento recientes
        $paActivities = DB::table('privacy.processing_activity')
            ->where('org_id', $orgId)
            ->select('pa_id as id', 'name', 'created_at', DB::raw("'Actividad de Tratamiento' as type"))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // DSARs recientes
        $dsarActivities = DB::table('privacy.dsar_request')
            ->where('org_id', $orgId)
            ->select('dsar_id as id', 'request_type as name', 'created_at', DB::raw("'DSAR' as type"))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Auditorías recientes
        $auditActivities = DB::table('audit.audit')
            ->where('org_id', $orgId)
            ->select('audit_id as id', 'audit_type as name', 'created_at', DB::raw("'Auditoría' as type"))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return $activities->merge($paActivities)
            ->merge($dsarActivities)
            ->merge($auditActivities)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();
    }

    // Alertas de vencimientos
    private function getAlerts($orgId)
    {
        $alerts = collect();

        // Alertas de DSAR vencidos
        $dsarAlerts = DB::table('privacy.dsar_request')
            ->where('org_id', $orgId)
            ->where('status', 'OPEN')
            ->where('due_at', '<', now())
            ->select(
                'dsar_id as id',
                'request_type as title',
                'due_at',
                DB::raw("'DSAR vencido' as type"),
                DB::raw("'high' as priority")
            )
            ->get();

        // Alertas de acciones correctivas vencidas
        $caAlerts = DB::table('audit.corrective_action as ca')
            ->join('audit.audit_finding as af', 'ca.finding_id', '=', 'af.finding_id')
            ->join('audit.audit as a', 'af.audit_id', '=', 'a.audit_id')
            ->where('a.org_id', $orgId)
            ->where('ca.status', '!=', 'CLOSED')
            ->where('ca.due_at', '<', now())
            ->select(
                'ca.ca_id as id',
                DB::raw("'Acción Correctiva vencida' as title"),
                'ca.due_at',
                DB::raw("'Acción Correctiva' as type"),
                DB::raw("'medium' as priority")
            )
            ->get();

        // Alertas de capacitaciones vencidas
        $trainingAlerts = DB::table('privacy.training_assignment as ta')
            ->join('privacy.training_course as tc', 'ta.course_id', '=', 'tc.course_id')
            ->where('tc.org_id', $orgId)
            ->where('ta.status', 'PENDING')
            ->where('ta.due_at', '<', now())
            ->select(
                'ta.assign_id as id',
                DB::raw("'Capacitación vencida' as title"),
                'ta.due_at',
                DB::raw("'Capacitación' as type"),
                DB::raw("'low' as priority")
            )
            ->get();

        return $alerts->merge($dsarAlerts)
            ->merge($caAlerts)
            ->merge($trainingAlerts)
            ->sortBy('due_at')
            ->values();
    }

    // Indicadores de performance
    private function getPerformanceIndicators($orgId)
    {
        return [
            'dsar_resolution_rate' => $this->getDsarResolutionRate($orgId),
            'audit_completion_rate' => $this->getAuditCompletionRate($orgId),
            'training_completion_rate' => $this->getTrainingCompletionRate($orgId),
            'risk_coverage' => $this->getRiskCoverage($orgId)
        ];
    }

    private function getDsarResolutionRate($orgId)
    {
        $stats = DB::table('privacy.dsar_request')
            ->where('org_id', $orgId)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'CLOSED' THEN 1 ELSE 0 END) as closed
            ")
            ->first();

        if ($stats->total > 0) {
            return round(($stats->closed / $stats->total) * 100, 2);
        }

        return 0;
    }

    private function getAuditCompletionRate($orgId)
    {
        $stats = DB::table('audit.audit')
            ->where('org_id', $orgId)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'COMPLETED' THEN 1 ELSE 0 END) as completed
            ")
            ->first();

        if ($stats->total > 0) {
            return round(($stats->completed / $stats->total) * 100, 2);
        }

        return 0;
    }

    private function getTrainingCompletionRate($orgId)
    {
        $stats = DB::table('privacy.training_assignment as ta')
            ->join('privacy.training_course as tc', 'ta.course_id', '=', 'tc.course_id')
            ->where('tc.org_id', $orgId)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN ta.status = 'COMPLETED' THEN 1 ELSE 0 END) as completed
            ")
            ->first();

        if ($stats->total > 0) {
            return round(($stats->completed / $stats->total) * 100, 2);
        }

        return 0;
    }

    private function getRiskCoverage($orgId)
    {
        $totalRisks = DB::table('risk.risk')
            ->where('org_id', $orgId)
            ->count();

        $risksWithDpia = DB::table('risk.dpia as d')
            ->join('privacy.processing_activity as pa', 'd.pa_id', '=', 'pa.pa_id')
            ->where('pa.org_id', $orgId)
            ->distinct('d.dpia_id')
            ->count();

        if ($totalRisks > 0) {
            return round(($risksWithDpia / $totalRisks) * 100, 2);
        }

        return 0;
    }

    // Datos para gráficos
    private function getChartsData($orgId)
    {
        return [
            'dsar_trend' => $this->getDsarTrend($orgId),
            'risk_distribution' => $this->getRiskDistribution($orgId),
            'audit_status' => $this->getAuditStatusChart($orgId)
        ];
    }

    private function getDsarTrend($orgId)
    {
        return DB::table('privacy.dsar_request')
            ->where('org_id', $orgId)
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("
                DATE_TRUNC('month', created_at) as month,
                COUNT(*) as count
            ")
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    private function getRiskDistribution($orgId)
    {
        return DB::table('risk.risk')
            ->where('org_id', $orgId)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
    }

    private function getAuditStatusChart($orgId)
    {
        return DB::table('audit.audit')
            ->where('org_id', $orgId)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
    }

    // API endpoints para componentes AJAX
    public function apiKPIs()
    {
        $orgId = $this->getOrgId();
        return response()->json($this->getKPIs($orgId));
    }

    public function apiAlerts()
    {
        $orgId = $this->getOrgId();
        return response()->json($this->getAlerts($orgId));
    }

    public function apiRecentActivity()
    {
        $orgId = $this->getOrgId();
        return response()->json($this->getRecentActivity($orgId));
    }
}
