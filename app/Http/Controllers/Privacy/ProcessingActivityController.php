<?php



namespace App\Http\Controllers\Privacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Privacy\ProcessingActivity;
use App\Models\Privacy\DataCategory;


class ProcessingActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = ProcessingActivity::orderBy('pa_id', 'desc')->get();

        return view('privacy.rat.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = DataCategory::orderBy('name')->get();

        return view('privacy.rat.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
