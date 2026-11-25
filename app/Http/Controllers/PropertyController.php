<?php

namespace App\Http\Controllers;

use App\Http\Requests\Crm\StorePropertyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = DB::table('properties')
            ->join('projects', 'projects.id', '=', 'properties.project_id')
            ->whereNull('properties.deleted_at')
            ->select('properties.*', 'projects.name as project_name')
            ->orderByDesc('properties.created_at')
            ->paginate(20);

        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        $projects = DB::table('projects')->orderBy('name')->get(['id', 'name']);
        return view('properties.create', compact('projects'));
    }

    public function store(StorePropertyRequest $request)
    {
        $data = $request->validated();
        $data['status'] = $data['status'] ?? 'Available';
        $data['features'] = isset($data['features']) ? json_encode(array_values($data['features'])) : null;
        $data['created_by'] = $request->user()->id;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        $id = DB::table('properties')->insertGetId($data);

        return redirect()->route('inventory.properties.show', $id)
            ->with('success', 'Property added to inventory.');
    }

    public function show(int $property)
    {
        $record = DB::table('properties')
            ->join('projects', 'projects.id', '=', 'properties.project_id')
            ->where('properties.id', $property)
            ->whereNull('properties.deleted_at')
            ->select('properties.*', 'projects.name as project_name')
            ->first();

        abort_if(!$record, 404);
        return view('properties.show', ['property' => $record]);
    }

    public function edit(int $property)
    {
        $record = DB::table('properties')->where('id', $property)->whereNull('deleted_at')->first();
        abort_if(!$record, 404);
        $projects = DB::table('projects')->orderBy('name')->get(['id', 'name']);
        return view('properties.edit', ['property' => $record, 'projects' => $projects]);
    }

    public function update(Request $request, int $property)
    {
        abort_if(!DB::table('properties')->where('id', $property)->whereNull('deleted_at')->exists(), 404);
        $data = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'unit_number' => ['required', 'string', 'max:100'],
            'carpet_area' => ['required', 'numeric', 'gt:0'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'type' => ['required', Rule::in(['Residential', 'Commercial', 'Plot'])],
            'status' => ['required', Rule::in(['Available', 'Blocked', 'Sold'])],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:100'],
        ]);
        $data['features'] = isset($data['features']) ? json_encode(array_values($data['features'])) : null;
        $data['updated_at'] = now();
        DB::table('properties')->where('id', $property)->update($data);

        return redirect()->route('inventory.properties.show', $property)->with('success', 'Property updated.');
    }

    public function destroy(int $property)
    {
        $updated = DB::table('properties')->where('id', $property)->whereNull('deleted_at')
            ->update(['deleted_at' => now(), 'updated_at' => now()]);
        abort_if(!$updated, 404);

        return redirect()->route('inventory.properties.index')->with('success', 'Property archived.');
    }
}
