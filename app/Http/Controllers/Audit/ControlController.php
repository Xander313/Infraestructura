<?php

namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\Audit\Control;
use App\Models\IAM\AppUser;
use Illuminate\Http\Request;

class ControlController extends Controller
{
    public function index()
    {
        $controls = Control::where('org_id', session('org_id'))
            ->with('owner')
            ->orderBy('name')
            ->get();

        return view('audit.controls.index', compact('controls'));
    }

    public function create()
    {
        $users = AppUser::all();

        return view('audit.controls.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'control_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'owner_user_id' => ['nullable', 'exists:' . AppUser::class . ',user_id'],
        ]);

        Control::create([
            'org_id' => session('org_id'),
            'code' => $request->code,
            'name' => $request->name,
            'control_type' => $request->control_type,
            'description' => $request->description,
            'owner_user_id' => $request->owner_user_id,
        ]);

        return redirect()->route('controls.index')
            ->with('success', 'Control creado correctamente.');
    }

    public function show(Control $control)
    {
        $this->authorizeControl($control);

        $control->load('owner', 'findings.correctiveActions');

        return view('audit.controls.show', compact('control'));
    }

    public function edit(Control $control)
    {
        $this->authorizeControl($control);

        $users = AppUser::all();

        return view('audit.controls.create', compact('control', 'users'));
    }

    public function update(Request $request, Control $control)
    {
        $this->authorizeControl($control);

        $request->validate([
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'control_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'owner_user_id' => ['nullable', 'exists:' . AppUser::class . ',user_id'],
        ]);

        $control->update($request->only([
            'code',
            'name',
            'control_type',
            'description',
            'owner_user_id',
        ]));

        return redirect()->route('controls.index')
            ->with('success', 'Control actualizado correctamente.');
    }

    /**
     * Validación de pertenencia a la organización activa
     */
    private function authorizeControl(Control $control)
    {
        if ($control->org_id !== session('org_id')) {
            abort(403, 'Acceso no autorizado a este control');
        }
    }
}
