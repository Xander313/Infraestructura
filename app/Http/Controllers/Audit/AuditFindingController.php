<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\Audit\AuditFinding;
use App\Models\Audit\Audit;
use App\Models\Audit\Control;
use Illuminate\Http\Request;

class AuditFindingController extends Controller
{
    public function index()
    {
        $findings = AuditFinding::with('audit', 'control')->orderBy('severity')->get();
        return view('audit.findings.index', compact('findings'));
    }

    public function create()
    {
        $audits = Audit::all();
        $controls = Control::all();
        return view('audit.findings.create', compact('audits', 'controls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'audit_id' => ['required', 'exists:' . Audit::class . ',audit_id'],
            'control_id' => ['nullable', 'exists:' . Control::class . ',control_id'],
            'severity' => 'required|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|string|max:50'
        ]);

        AuditFinding::create($request->all());

        return redirect()->route('findings.index')
            ->with('success', 'Hallazgo creado correctamente.');
    }

    public function show(AuditFinding $finding)
    {
        $finding->load('audit', 'control', 'correctiveActions.owner');
        return view('audit.findings.show', compact('finding'));
    }

    public function edit(AuditFinding $finding)
    {
        $audits = Audit::all();
        $controls = Control::all();
        return view('audit.findings.create', compact('finding', 'audits', 'controls'));
    }

    public function update(Request $request, AuditFinding $finding)
    {
        $request->validate([
            'audit_id' => ['required', 'exists:' . Audit::class . ',audit_id'],
            'control_id' => ['nullable', 'exists:' . Control::class . ',control_id'],
            'severity' => 'required|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|string|max:50'
        ]);

        $finding->update($request->all());

        return redirect()->route('findings.index')
            ->with('success', 'Hallazgo actualizado correctamente.');
    }

    public function destroy(AuditFinding $finding)
    {
        $finding->delete();
        return redirect()->route('findings.index')
            ->with('success', 'Hallazgo eliminado correctamente.');
    }
}
