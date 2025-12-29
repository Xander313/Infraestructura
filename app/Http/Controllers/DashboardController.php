<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Dashboard principal
     */
    public function index()
    {
        $orgId = $this->getCurrentOrgId();
        
        // Cache por 5 minutos
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

    /**
     * Obtener org_id actual (SIN autenticación por ahora)
     */
    private function getCurrentOrgId()
    {
        // Cuando haya login: session('org_id') o Auth::user()->org_id
        // Por ahora: primera org de la BD o valor por defecto
        try {
            $org = DB::table('core.org')->first();
            return $org ? $org->org_id : 1;
        } catch (\Exception $e) {
            return 1; // Valor por defecto para desarrollo
        }
    }

    /**
     * KPIs principales
     */
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

    /**
     * #Actividades de Tratamiento
     */
    private function getProcessingActivitiesCount($orgId)
    {
        try {
            return DB::table('privacy.processing_activity')
                ->where('org_id', $orgId)
                ->count();
        } catch (\Exception $e) {
            return 0; // Fallback para desarrollo
        }
    }

    /**
     * DSAR abiertos/vencidos
     */
    private function getDsarRequestsStats($orgId)
    {
        try {
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
        } catch (\Exception $e) {
            return ['total' => 0, 'open' => 0, 'overdue' => 0];
        }
    }

    /**
     * Riesgos por severidad
     */
    private function getRisksBySeverity($orgId)
    {
        try {
            $risks = DB::table('risk.risk')
                ->where('org_id', $orgId)
                ->select('risk_type as severity', DB::raw('COUNT(*) as count'))
                ->groupBy('risk_type')
                ->orderBy('count', 'desc')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->severity => $item->count];
                })
                ->toArray();

            return $risks;
        } catch (\Exception $e) {
            return ['HIGH' => 0, 'MEDIUM' => 0, 'LOW' => 0];
        }
    }

    /**
     * Auditorías en curso
     */
    private function getAuditsInProgress($orgId)
    {
        try {
            return DB::table('audit.audit')
                ->where('org_id', $orgId)
                ->whereIn('status', ['PLANNED', 'IN_PROGRESS'])
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Capacitaciones pendientes
     */
    private function getPendingTrainings($orgId)
    {
        try {
            return DB::table('privacy.training_assignment as ta')
                ->join('privacy.training_course as tc', 'ta.course_id', '=', 'tc.course_id')
                ->where('tc.org_id', $orgId)
                ->where('ta.status', 'PENDING')
                ->where('ta.due_at', '>', now())
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Actividad reciente
     */
    private function getRecentActivity($orgId)
    {
        try {
            $activities = collect();

            // Actividades de tratamiento
            $paActivities = DB::table('privacy.processing_activity')
                ->where('org_id', $orgId)
                ->select('pa_id as id', 'name', 'created_at', DB::raw("'Actividad de Tratamiento' as type"))
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // DSARs
            $dsarActivities = DB::table('privacy.dsar_request')
                ->where('org_id', $orgId)
                ->select('dsar_id as id', 'request_type as name', 'created_at', DB::raw("'DSAR' as type"))
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Auditorías
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
        } catch (\Exception $e) {
            return collect(); // Colección vacía si hay error
        }
    }

    /**
     * Alertas de vencimientos
     */
    private function getAlerts($orgId)
    {
        try {
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

            // Alertas de acciones correctivas
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

            // Alertas de capacitaciones
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
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Indicadores de performance
     */
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
        try {
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
        } catch (\Exception $e) {
            // Ignorar error
        }
        return 0;
    }

    private function getAuditCompletionRate($orgId)
    {
        try {
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
        } catch (\Exception $e) {
            // Ignorar error
        }
        return 0;
    }

    private function getTrainingCompletionRate($orgId)
    {
        try {
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
        } catch (\Exception $e) {
            // Ignorar error
        }
        return 0;
    }

    private function getRiskCoverage($orgId)
    {
        try {
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
        } catch (\Exception $e) {
            // Ignorar error
        }
        return 0;
    }

    /**
     * Datos para gráficos
     */
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
        try {
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
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getRiskDistribution($orgId)
    {
        try {
            return DB::table('risk.risk')
                ->where('org_id', $orgId)
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function getAuditStatusChart($orgId)
    {
        try {
            return DB::table('audit.audit')
                ->where('org_id', $orgId)
                ->select('status', DB::raw('COUNT(*) as count'))
                ->groupBy('status')
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * API endpoints para AJAX
     */
    public function apiKPIs()
    {
        $orgId = $this->getCurrentOrgId();
        return response()->json($this->getKPIs($orgId));
    }

    public function apiAlerts()
    {
        $orgId = $this->getCurrentOrgId();
        return response()->json($this->getAlerts($orgId));
    }

    public function apiRecentActivity()
    {
        $orgId = $this->getCurrentOrgId();
        return response()->json($this->getRecentActivity($orgId));
    }
}