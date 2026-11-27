<?php

namespace App\Http\Controllers;

use App\Models\DirectoryEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    private const PROJECT_STATUSES = ['Under Construction', 'Ready Possession', 'Pre-Launch'];

    public function index()
    {
        $projects = DB::table('projects')
            ->select('projects.*')
            ->selectSub(
                DB::table('properties')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('properties.project_id', 'projects.id')
                    ->whereNull('properties.deleted_at'),
                'properties_count'
            )
            ->orderBy('projects.name')
            ->paginate(20);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $developers = DirectoryEntity::ownedBy((int) auth()->id())
            ->where('entity_type', 'company')->orderBy('display_name')->get(['id', 'display_name']);
        return view('projects.create', compact('developers'));
    }

    public function show(int $project)
    {
        $record = DB::table('projects')->leftJoin('directory_entities as developer', 'developer.id', '=', 'projects.developer_directory_entity_id')\n            ->select('projects.*', 'developer.display_name as developer_name')\n            ->where('projects.id', $project)->first();
        abort_if(!$record, 404);

        $properties = DB::table('properties')->where('project_id', $project)
            ->whereNull('deleted_at')->orderBy('unit_number')->get();
        $phases = DB::table('project_phases')->where('project_id', $project)
            ->whereNull('deleted_at')->orderBy('phase_name')->get();
        $professionals = DB::table('project_professionals')
            ->join('directory_entities', 'directory_entities.id', '=', 'project_professionals.directory_entity_id')
            ->where('project_professionals.project_id', $project)
            ->where('project_professionals.owner_user_id', auth()->id())
            ->whereNull('project_professionals.deleted_at')
            ->whereNull('directory_entities.deleted_at')
            ->select('project_professionals.*', 'directory_entities.display_name', 'directory_entities.entity_type')
            ->orderBy('project_professionals.professional_role')->orderBy('directory_entities.display_name')->get();
        $availableProfessionals = DirectoryEntity::ownedBy((int) auth()->id())
            ->where('status', 'active')->orderBy('display_name')->get(['id', 'display_name', 'entity_type']);
        $documents = DB::table('project_documents')->where('project_id', $project)
            ->whereNull('deleted_at')->where(function ($q) {
                $q->whereNull('uploaded_by')->orWhere('uploaded_by', auth()->id());
            })->orderByDesc('created_at')->get();
        $approvals = DB::table('project_approvals')->where('project_id', $project)
            ->whereNull('deleted_at')->orderBy('approval_type')->get();
        $updates = DB::table('project_progress_updates')->where('project_id', $project)
            ->orderByDesc('period_end')->limit(8)->get();
        $checks = DB::table('project_due_diligence_checks')->where('project_id', $project)
            ->whereNull('deleted_at')->orderBy('check_type')->get();

        return view('projects.show', [
            'project' => $record, 'properties' => $properties, 'phases' => $phases,
            'professionals' => $professionals, 'availableProfessionals' => $availableProfessionals,
            'documents' => $documents, 'approvals' => $approvals, 'updates' => $updates, 'checks' => $checks,
        ]);
    }

    public function edit(int $project)
    {
        $record = DB::table('projects')->where('id', $project)->first();
        abort_if(!$record, 404);
        $developers = DirectoryEntity::ownedBy((int) auth()->id())
            ->where('entity_type', 'company')->orderBy('display_name')->get(['id', 'display_name']);
        return view('projects.edit', ['project' => $record, 'developers' => $developers]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedProject($request);
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;
        $data['created_at'] = now();
        $data['updated_at'] = now();
        $id = DB::table('projects')->insertGetId($data);

        return redirect()->route('inventory.projects.show', $id)
            ->with('success', 'Project created. Complete its phases, approvals and due-diligence records next.');
    }

    public function update(Request $request, int $project)
    {
        abort_if(!DB::table('projects')->where('id', $project)->exists(), 404);
        $data = $this->validatedProject($request, $project);
        $data['updated_by'] = $request->user()->id;
        $data['updated_at'] = now();
        DB::table('projects')->where('id', $project)->update($data);

        return redirect()->route('inventory.projects.show', $project)->with('success', 'Project updated.');
    }

    public function addProfessional(Request $request, int $project)
    {
        abort_if(!DB::table('projects')->where('id', $project)->exists(), 404);
        $data = $request->validate([
            'directory_entity_id' => [
                'required', 'integer',
                Rule::exists('directory_entities', 'id')->where('owner_user_id', $request->user()->id)->whereNull('deleted_at'),
            ],
            'professional_role' => ['required', Rule::in([
                'engineer', 'architect', 'structural_engineer', 'chartered_accountant',
                'contractor', 'real_estate_agent', 'other',
            ])],
            'is_primary' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($data, $request, $project) {
            $entity = DirectoryEntity::ownedBy((int) $request->user()->id)->findOrFail($data['directory_entity_id']);
            $existing = DB::table('project_professionals')
                ->where('project_id', $project)->whereNull('project_phase_id')
                ->where('directory_entity_id', $entity->id)
                ->where('professional_role', $data['professional_role'])
                ->where('owner_user_id', $request->user()->id)->whereNull('deleted_at')->first();
            if ($existing) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'directory_entity_id' => 'This professional is already linked to this project in this role.',
                ]);
            }

            if (!empty($data['is_primary'])) {
                DB::table('project_professionals')->where('project_id', $project)
                    ->whereNull('project_phase_id')->where('professional_role', $data['professional_role'])
                    ->where('owner_user_id', $request->user()->id)->whereNull('deleted_at')
                    ->update(['is_primary' => false, 'updated_at' => now()]);
            }

            DB::table('project_professionals')->insert([
                'project_id' => $project,
                'project_phase_id' => null,
                'directory_entity_id' => $entity->id,
                'owner_user_id' => $request->user()->id,
                'professional_role' => $data['professional_role'],
                'is_primary' => !empty($data['is_primary']),
                'engagement_status' => 'active',
                'notes' => $data['notes'] ?? null,
                'created_by' => $request->user()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('inventory.projects.show', $project)->with('success', 'Professional linked from Contacts / Companies 360°.');
    }

    public function removeProfessional(Request $request, int $project, int $professional)
    {
        $deleted = DB::table('project_professionals')
            ->where('id', $professional)->where('project_id', $project)
            ->where('owner_user_id', $request->user()->id)->whereNull('deleted_at')
            ->update(['deleted_at' => now(), 'updated_at' => now()]);
        abort_if(!$deleted, 404);

        return redirect()->route('inventory.projects.show', $project)
            ->with('success', 'Project association removed. The source Contact / Company record was not deleted.');
    }

    private function validatedProject(Request $request, ?int $project = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'maharera_reg_no' => ['nullable', 'string', 'max:255', Rule::unique('projects', 'maharera_reg_no')->ignore($project)],
            'location_area' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:2000'],
            'status' => ['required', Rule::in(self::PROJECT_STATUSES)],
            'developer_directory_entity_id' => [
                'nullable', 'integer',
                Rule::exists('directory_entities', 'id')->where('owner_user_id', $request->user()->id)->where('entity_type', 'company')->whereNull('deleted_at'),
            ],
            'project_type' => ['nullable', Rule::in(['residential', 'commercial', 'mixed_use', 'plotted_development', 'redevelopment', 'other'])],
            'description' => ['nullable', 'string', 'max:10000'],
            'launch_date' => ['nullable', 'date'],
            'proposed_completion_date' => ['nullable', 'date'],
            'actual_completion_date' => ['nullable', 'date'],
            'land_area_sqm' => ['nullable', 'numeric', 'gt:0'],
            'total_buildings' => ['nullable', 'integer', 'min:0'],
            'total_wings' => ['nullable', 'integer', 'min:0'],
            'total_units' => ['nullable', 'integer', 'min:0'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'website_url' => ['nullable', 'url', 'max:500'],
        ]);
    }
}
