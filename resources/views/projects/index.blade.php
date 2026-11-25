@extends('layouts.app')

@section('content')
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 pt-2 sm:px-6 lg:px-8">
        <div><h1 class="text-2xl font-bold text-slate-900">Projects</h1><p class="mt-1 text-sm text-slate-500">Manage developments and their property inventory</p></div>
        <a href="{{ route('inventory.projects.create') }}" class="rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">+ Add Project</a>
    </div>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))<div class="mb-5 rounded-lg bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>@endif
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50"><tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Project</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Location</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">MahaRERA No.</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Properties</th>
                        </tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($projects as $project)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-4 text-sm font-semibold text-slate-900"><a class="text-blue-700 hover:underline" href="{{ route('inventory.projects.show', $project->id) }}"><a class="font-semibold text-blue-700 hover:underline" href="{{ route('inventory.projects.show', $project->id) }}">{{ $project->name }}</a></a></td>
                                    <td class="px-5 py-4 text-sm text-slate-700">{{ $project->location_area }}</td>
                                    <td class="px-5 py-4 text-sm text-slate-700">{{ $project->maharera_reg_no ?: '—' }}</td>
                                    <td class="px-5 py-4 text-sm text-slate-700">{{ $project->status }}</td>
                                    <td class="px-5 py-4 text-right text-sm tabular-nums text-slate-700">{{ $project->properties_count }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">No projects have been added yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-100 px-5 py-4">{{ $projects->links() }}</div>
            </div>
        </div>
    </div>
@endsection
