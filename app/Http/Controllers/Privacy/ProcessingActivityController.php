<?php



namespace App\Http\Controllers\Privacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Privacy\ProcessingActivity;
use App\Models\Privacy\DataCategory;
use App\Models\Privacy\Recipient;
use App\Models\Privacy\Country;
use Illuminate\Support\Facades\DB;


class ProcessingActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = ProcessingActivity::orderBy('pa_id', 'desc')->get();

        //dd($activities);
        return view('privacy.rat.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


        $categories = DataCategory::all();
        $recipients = Recipient::all();
        $countries = Country::all();

        return view('privacy.rat.create', compact('categories', 'recipients', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {


            $activity = ProcessingActivity::create([
                /*'org_id' => 1, aqui se espera el id de la session de org se coloca un 1 para poder insertar TEMPORALMENTE*/
                'org_id' => 1,
                /*'owner_unit_id' => auth()->user()->unit_id,----aqui se espera el id de la session del user actual se coloca un 1 para poder insertar TEMPORALMENTE*/
                'owner_unit_id' => 1,
                'name' => $request->name
            ]);


            

            if ($request->has('data_categories')) {
                foreach ($request->data_categories as $cat_id => $data) {
                    if (isset($data['checked'])) {
                        DB::table('privacy.pa_data_category')->insert([
                            'pa_id' => $activity->pa_id,
                            'data_cat_id' => $cat_id,
                            'collection_source' => $data['collection_source'] ?? 'N/A'
                        ]);
                    }
                }
            }


            if ($request->has('retention_rules')) {
                foreach ($request->retention_rules as $rule) {
                    DB::table('privacy.retention_rule')->insert([
                        'pa_id' => $activity->pa_id,
                        'retention_period_days' => $rule['retention_period_days'] ?? null,
                        'trigger_event' => $rule['trigger_event'] ?? null,
                        /*
                        'disposal_method' => $rule['disposal_method'] ?? null,
                        'legal_hold_flag' => false*/
                        'disposal_method' => $rule['disposal_method'] ?? null,
                        'legal_hold_flag' => isset($rule['legal_hold_flag']) ? true : false
                    ]);
                }
            }


            if ($request->has('transfers')) {
                foreach ($request->transfers as $transfer) {
                    DB::table('privacy.transfer')->insert([
                        'pa_id' => $activity->pa_id,
                        'recipient_id' => $transfer['recipient_id'] ?? null,
                        'country_id' => $transfer['country_id'] ?? null,
                        'transfer_type' => $transfer['transfer_type'] ?? null,
                        'safeguard' => $transfer['safeguard'] ?? null,
                        'legal_basis_text' => $transfer['legal_basis_text'] ?? null,
                        'created_at' => now()
                    ]);
                }
            }
        });

        return redirect()->route('rat.index')->with('success', 'Actividad creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Obtener actividad con categorías cargadas
        $activity = ProcessingActivity::with('categories')->findOrFail($id);

        // Obtener datos para los selects / checkboxes
        $categories = DataCategory::all();
        $recipients = Recipient::all();
        $countries = Country::all();

        // Obtener categorías seleccionadas y su collection_source desde el pivot
        $selectedCategories = $activity->categories->pluck('data_cat_id')->toArray();
        $categoryPivot = $activity->categories->pluck('pivot.collection_source', 'data_cat_id')->toArray();

        return view('privacy.rat.edit', compact(
            'activity',
            'categories',
            'recipients',
            'countries',
            'selectedCategories',
            'categoryPivot'
        ));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'data_categories' => 'required|array',
            'retention_rules' => 'required|array',
            'transfers' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $id) {
            $activity = ProcessingActivity::findOrFail($id);
            $activity->update([
                'name' => $request->name,
                'org_id' => 1, // Ajusta según tu contexto
                'owner_unit_id' => 1, // Ajusta según tu contexto
            ]);

            // Actualizar categorías
            $activity->categories()->sync($request->data_categories);

            // Actualizar Retention Rules
            if ($request->has('retention_rules')) {
                // Borrar existentes
                $activity->retentionRules()->delete();

                foreach ($request->retention_rules as $rule) {
                    $activity->retentionRules()->create([
                        'retention_period_days' => $rule['retention_period_days'] ?? null,
                        'trigger_event' => $rule['trigger_event'] ?? null,
                        'disposal_method' => $rule['disposal_method'] ?? null,
                        'legal_hold_flag' => $rule['legal_hold_flag'] ?? false,
                    ]);
                }
            }

            // Actualizar Transferencias
            if ($request->has('transfers')) {
                $activity->transfers()->delete();

                foreach ($request->transfers as $t) {
                    $activity->transfers()->create([
                        'recipient_id' => $t['recipient_id'] ?? null,
                        'country_id' => $t['country_id'] ?? null,
                        'transfer_type' => $t['transfer_type'] ?? 'N/A',
                        'safeguard' => $t['safeguard'] ?? 'N/A',
                        'legal_basis_text' => $t['legal_basis_text'] ?? 'N/A',
                    ]);
                }
            }
        });

        return redirect()->route('rat.index')->with('success', 'Actividad actualizada correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activity = ProcessingActivity::findOrFail($id);

        DB::transaction(function () use ($activity) {
            // Eliminar subrecursos (relaciones)
         //  $activity->categories()->detach(); SE ESPERA EL MODEO CATEGORIES PARA REFERCIAR Y ELIMINAR
            $activity->retentionRules()->delete();
            $activity->transfers()->delete();

            // Eliminar actividad
            $activity->delete();
        });

        return redirect()->route('rat.index')->with('success', 'Actividad eliminada correctamente.');
    }
}
