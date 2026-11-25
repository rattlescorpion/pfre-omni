<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = DB::table('projects')
            ->leftJoin('properties', function ($join) {\n                $join->on('properties.project_id', '=', 'projects.id')\n                    ->whereNull('properties.deleted_at');\n            })
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

    public function show(int $project)
    {
        $record = DB::table('projects')->where('id', $project)->first();
        abort_if(!$record, 404);
        $properties = DB::table('properties')->where('project_id', $project)->whereNull('deleted_at')->orderBy('unit_number')->get();
        return view('projects.show', ['project' => $record, 'properties' => $properties]);
    }

    public function edit(int $project)
    {
        $record = DB::table('projects')->where('id', $project)->first();
        abort_if(!$record, 404);
        return view('projects.edit', ['project' => $record]);
    }

    public function update(Request $request, int $project)
    {
        abort_if(!DB::table('projects')->where('id', $project)->exists(), 404);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'maharera_reg_no' => ['nullable', 'string', 'max:255', Rule::unique('projects', 'maharera_reg_no')->ignore($project)],
            'location_area' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:2000'],
            'status' => ['required', Rule::in(['Under Construction', 'Ready Possession', 'Pre-Launch'])],
        ]);
        $data['updated_at'] = now();
        DB::table('projects')->where('id', $project)->update($data);
        return redirect()->route('inventory.projects.show', $project)->with('success', 'Project updated.');
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
        $id = DB::table('projects')->insertGetId($data);
        return redirect()->route('inventory.projects.show', $id)
            ->with('success', 'Project created successfully. You can now add its properties.');
    }
}
