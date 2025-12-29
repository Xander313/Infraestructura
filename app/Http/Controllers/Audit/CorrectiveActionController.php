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
        $actions = CorrectiveAction::with('finding.audit', 'owner')->orderBy('due_at')->get();
        return view('audit.corrective_actions.index', compact('actions'));
    }

    public function create()
    {
        $findings = AuditFinding::all();
        $users = AppUser::all();
        return view('audit.corrective_actions.create', compact('findings', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'finding_id' => ['required', 'exists:' . AuditFinding::class . ',finding_id'],
            'owner_user_id' => ['nullable', 'exists:' . AppUser::class . ',user_id'],
            'due_at' => 'nullable|date',
            'status' => 'required|string|max:50',
            'closed_at' => 'nullable|date',
            'outcome' => 'nullable|string'
        ]);

        CorrectiveAction::create($request->all());

        return redirect()->route('corrective_actions.index')
            ->with('success', 'Acción correctiva creada correctamente.');
    }

    public function show(CorrectiveAction $action)
    {
        $action->load('finding.audit', 'owner');
        return view('audit.corrective_actions.show', compact('action'));
    }

    public function edit($id)
{
    $action = CorrectiveAction::findOrFail($id);
    $findings = AuditFinding::all(); // Asegúrate de importar la clase
    $users = \App\Models\IAM\AppUser::all(); // Asegúrate de importar

    return view('audit.corrective_actions.create', compact('action', 'findings', 'users'));
}


    public function update(Request $request, CorrectiveAction $action)
    {
        $request->validate([
            'finding_id' => 'required|exists:App\Models\Audit\AuditFinding,finding_id',
            'owner_user_id' => 'nullable|exists:App\Models\IAM\AppUser,user_id',
            'due_at' => 'nullable|date',
            'status' => 'required|string|max:50',
            'closed_at' => 'nullable|date',
            'outcome' => 'nullable|string'
        ]);

        $action->update($request->all());

        return redirect()->route('corrective_actions.index')
            ->with('success', 'Acción correctiva actualizada correctamente.');
    }

    public function destroy(CorrectiveAction $action)
    {
        $action->delete();
        return redirect()->route('corrective_actions.index')
            ->with('success', 'Acción correctiva eliminada correctamente.');
    }
}
