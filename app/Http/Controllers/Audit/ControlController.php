<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\Audit\Control;
use App\Models\Core\Org;
use App\Models\IAM\AppUser;
use Illuminate\Http\Request;

class ControlController extends Controller
{
    public function index()
    {
        $controls = Control::with('org', 'owner')->orderBy('name')->get();
        return view('audit.controls.index', compact('controls'));
    }

    public function create()
    {
        $orgs = Org::all();
        $users = AppUser::all();
        return view('audit.controls.create', compact('orgs', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'org_id' => ['required', 'exists:' . Org::class . ',org_id'],
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'control_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'owner_user_id' => ['nullable', 'exists:' . AppUser::class . ',user_id']
        ]);

        Control::create($request->all());

        return redirect()->route('controls.index')
            ->with('success', 'Control creado correctamente.');
    }

    public function show(Control $control)
    {
        $control->load('org', 'owner', 'findings.correctiveActions');
        return view('audit.controls.show', compact('control'));
    }

    public function edit(Control $control)
    {
        $orgs = Org::all();
        $users = AppUser::all();
        return view('audit.controls.create', compact('control', 'orgs', 'users'));
    }

    public function update(Request $request, Control $control)
    {
        $request->validate([
            'org_id' => ['required', 'exists:' . Org::class . ',org_id'],
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'control_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'owner_user_id' => ['nullable', 'exists:' . AppUser::class . ',user_id']
        ]);

        $control->update($request->all());

        return redirect()->route('controls.index')
            ->with('success', 'Control actualizado correctamente.');
    }

    public function destroy(Control $control)
    {
        $control->delete();
        return redirect()->route('controls.index')
            ->with('success', 'Control eliminado correctamente.');
    }
}
