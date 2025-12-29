<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Org;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrgController extends Controller
{
    public function index()
    {
        $orgs = Org::orderBy('name')->get();
        return view('core.org.index', compact('orgs'));
    }

    public function create()
    {
        return view('core.org.create');
    }
    
    public function show(Org $org)
    {
        return view('core.org.show', compact('org'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ruc' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique(Org::class, 'ruc'),
            ],
            'industry' => 'nullable|string|max:255',
        ]);

        $org = Org::create(
            $request->only('name', 'ruc', 'industry')
        );

        session(['org_id' => $org->org_id]);

        return redirect()
            ->route('orgs.index')
            ->with('success', 'Organización creada y activada.');
    }

    public function edit(Org $org)
    {
        return view('core.org.edit', compact('org'));
    }

    public function update(Request $request, Org $org)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ruc' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique(Org::class, 'ruc')
                    ->ignore($org->org_id, 'org_id'),
            ],
            'industry' => 'nullable|string|max:255',
        ]);

        $org->update(
            $request->only('name', 'ruc', 'industry')
        );

        return redirect()
            ->route('orgs.index')
            ->with('success', 'Organización actualizada correctamente.');
    }

    public function destroy(Org $org)
    {
        // Evita borrar la organización activa
        if (session('org_id') == $org->org_id) {
            return redirect()
                ->route('orgs.index')
                ->with('error', 'No puedes eliminar la organización activa.');
        }

        $org->delete();

        return redirect()
            ->route('orgs.index')
            ->with('success', 'Organización eliminada correctamente.');
    }
}
