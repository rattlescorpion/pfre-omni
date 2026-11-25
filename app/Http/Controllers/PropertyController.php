<?php

namespace App\\Http\\Controllers;

use App\\Http\\Requests\\Crm\\StorePropertyRequest;
use Illuminate\\Support\\Facades\\DB;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = DB::table('properties')
            ->join('projects', 'projects.id', '=', 'properties.project_id')
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
        $data['features'] = isset($data['features'])
            ? json_encode(array_values($data['features']))
            : null;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('properties')->insert($data);

        return redirect()->route('inventory.properties.index')
            ->with('success', 'Property added to inventory.');
    }
}
