<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\Audit\CorrectiveAction;
use App\Models\Audit\AuditFinding;
use App\Models\IAM\AppUser;
use Illuminate\Http\Request;

class CorrectiveActionController extends Controller
{
    public function index()
    {
        $actions = CorrectiveAction::whereHas('finding.audit', function ($q) {
                $q->where('org_id', session('org_id'));
            })
            ->with('finding.audit', 'owner')
            ->orderBy('due_at')
            ->get();

        return view('audit.corrective_actions.index', compact('actions'));
    }

    public function create()
    {
        $findings = AuditFinding::whereHas('audit', function ($q) {
                $q->where('org_id', session('org_id'));
            })
            ->get();

        $users = AppUser::all();

        return view('audit.corrective_actions.create', compact('findings', 'users'));
    }

    public function store(Request $request)
    {
        if (!session()->has('org_id')) {
            abort(403, 'No hay organización activa');
        }

        $request->validate([
            'finding_id' => ['required', 'exists:' . AuditFinding::class . ',finding_id'],
            'owner_user_id' => ['nullable', 'exists:' . AppUser::class . ',user_id'],
            'due_at' => 'nullable|date',
            'status' => 'required|string|max:50',
            'closed_at' => 'nullable|date',
            'outcome' => 'nullable|string',
        ]);

        $finding = AuditFinding::where('finding_id', $request->finding_id)
            ->whereHas('audit', function ($q) {
                $q->where('org_id', session('org_id'));
            })
            ->firstOrFail();

        CorrectiveAction::create([
            'finding_id' => $finding->finding_id,
            'owner_user_id' => $request->owner_user_id,
            'due_at' => $request->due_at,
            'status' => $request->status,
            'closed_at' => $request->closed_at,
            'outcome' => $request->outcome,
        ]);

        return redirect()->route('corrective_actions.index')
            ->with('success', 'Acción correctiva creada correctamente.');
    }


    public function show(CorrectiveAction $action)
    {
        $this->authorizeAction($action);

        $action->load('finding.audit', 'owner');

        return view('audit.corrective_actions.show', compact('action'));
    }

    public function edit(CorrectiveAction $action)
    {
        $this->authorizeAction($action);

        $findings = AuditFinding::whereHas('audit', function ($q) {
                $q->where('org_id', session('org_id'));
            })
            ->get();

        $users = AppUser::all();

        return view('audit.corrective_actions.create', compact('action', 'findings', 'users'));
    }

    public function update(Request $request, CorrectiveAction $action)
    {
        $this->authorizeAction($action);

        $request->validate([
            'finding_id' => ['required', 'exists:' . AuditFinding::class . ',finding_id'],
            'owner_user_id' => ['nullable', 'exists:' . AppUser::class . ',user_id'],
            'due_at' => 'nullable|date',
            'status' => 'required|string|max:50',
            'closed_at' => 'nullable|date',
            'outcome' => 'nullable|string',
        ]);

        $action->update($request->only([
            'finding_id',
            'owner_user_id',
            'due_at',
            'status',
            'closed_at',
            'outcome',
        ]));

        return redirect()->route('corrective_actions.index')
            ->with('success', 'Acción correctiva actualizada correctamente.');
    }

    /**
     * No se elimina evidencia: se marca como cerrada
     */
    public function destroy(CorrectiveAction $action)
    {
        $this->authorizeAction($action);

        $action->update([
            'status' => 'CERRADA',
            'closed_at' => now(),
        ]);

        return redirect()->route('corrective_actions.index')
            ->with('success', 'Acción correctiva cerrada correctamente.');
    }

    /**
     * Validación multi-tenant
     */
    private function authorizeAction(CorrectiveAction $action)
    {
        if ($action->finding->audit->org_id !== session('org_id')) {
            abort(403, 'Acceso no autorizado a esta acción correctiva');
        }
    }
}
