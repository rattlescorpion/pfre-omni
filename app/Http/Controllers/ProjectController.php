<?php

namespace App\\Http\\Controllers;

use Illuminate\\Http\\Request;
use Illuminate\\Support\\Facades\\DB;
use Illuminate\\Validation\\Rule;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = DB::table('projects')
            ->leftJoin('properties', 'properties.project_id', '=', 'projects.id')
            ->select('projects.*', DB::raw('COUNT(properties.id) as properties_count'))
            ->groupBy('projects.id', 'projects.name', 'projects.maharera_reg_no', 'projects.location_area', 'projects.address', 'projects.status', 'projects.created_at', 'projects.updated_at')
            ->orderBy('projects.name')
            ->paginate(20);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'maharera_reg_no' => ['nullable', 'string', 'max:255', 'unique:projects,maharera_reg_no'],
            'location_area' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:2000'],
            'status' => ['required', Rule::in(['Under Construction', 'Ready Possession', 'Pre-Launch'])],
        ]);

        $data['created_at'] = now();
        $data['updated_at'] = now();
        DB::table('projects')->insert($data);

        return redirect()->route('inventory.projects.index')
            ->with('success', 'Project created successfully. You can now add its properties.');
    }
}
