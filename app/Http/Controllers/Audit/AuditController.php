<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\Audit\Audit;
use App\Models\Core\Org;
use App\Models\IAM\AppUser;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
        $audits = Audit::with('org', 'auditor')->orderBy('planned_at')->get();
        return view('audit.audits.index', compact('audits'));
    }

    public function create()
    {
        $orgs = Org::all();
        $users = AppUser::all();
        return view('audit.audits.create', compact('orgs', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'org_id' => ['required', 'exists:' . Org::class . ',org_id'],
            'audit_type' => 'required|string|max:255',
            'scope' => 'nullable|string',
            'auditor_user_id' => ['nullable', 'exists:' . AppUser::class . ',user_id'],
            'planned_at' => 'nullable|date',
            'executed_at' => 'nullable|date',
            'status' => 'required|string|max:50'
        ]);

        Audit::create($request->all());

        return redirect()->route('audits.index')
            ->with('success', 'Auditoría creada correctamente.');
    }

    public function show(Audit $audit)
    {
        $audit->load('org', 'auditor', 'findings.correctiveActions');
        return view('audit.audits.show', compact('audit'));
    }

    public function edit(Audit $audit)
    {
        $orgs = Org::all();
        $users = AppUser::all();
        return view('audit.audits.create', compact('audit', 'orgs', 'users'));
    }

    public function update(Request $request, Audit $audit)
    {
        $request->validate([
            'org_id' => ['required', 'exists:' . Org::class . ',org_id'],
            'audit_type' => 'required|string|max:255',
            'scope' => 'nullable|string',
            'auditor_user_id' => ['nullable', 'exists:' . AppUser::class . ',user_id'],
            'planned_at' => 'nullable|date',
            'executed_at' => 'nullable|date',
            'status' => 'required|string|max:50'
        ]);

        $audit->update($request->all());

        return redirect()->route('audits.index')
            ->with('success', 'Auditoría actualizada correctamente.');
    }

    public function destroy(Audit $audit)
    {
        $audit->delete();
        return redirect()->route('audits.index')
            ->with('success', 'Auditoría eliminada correctamente.');
    }
}
