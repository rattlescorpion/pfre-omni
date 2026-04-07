<body class="bg-slate-100 p-8">
    <div class="max-w-full mx-auto bg-white rounded-xl shadow-lg overflow-hidden border border-slate-200">
        <div class="bg-indigo-700 p-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-white">eRegistration Master List</h2>
                <p class="text-indigo-100 text-sm">Real-time sync with MahaRERA & Property Inventory</p>
            </div>
            <div class="flex gap-3">
                <button class="bg-indigo-600 text-white border border-indigo-400 px-4 py-2 rounded font-medium hover:bg-indigo-500 transition">
                    📥 Export XLSX
                </button>
                <a href="{{ route('crm.eregistrations.create') }}" class="bg-white text-indigo-700 px-4 py-2 rounded font-bold shadow-sm hover:bg-indigo-50 transition">
                    + New Registration
                </a>
            </div>
        </div>

        <div class="p-4 bg-slate-50 border-b flex gap-4 items-center">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                    🔍
                </span>
                <input type="text" placeholder="Search by Name, PAN, or Unit No..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
            </div>
            <select class="border rounded-lg px-3 py-2 text-sm text-slate-600 outline-none">
                <option>All Projects</option>
                <option>MahaRERA Registered</option>
            </select>
            <select class="border rounded-lg px-3 py-2 text-sm text-slate-600 outline-none">
                <option>Status: All</option>
                <option>Draft</option>
                <option>Verified</option>
                <option>Stamping</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b">
                    <tr class="text-slate-600 text-xs uppercase tracking-wider">
                        <th class="p-4 border-r font-semibold">Party / Role</th>
                        <th class="p-4 border-r font-semibold">Linked Property</th>
                        <th class="p-4 border-r font-semibold">KYC (Identity)</th>
                        <th class="p-4 border-r font-semibold">Contact Details</th>
                        <th class="p-4 border-r font-semibold">Status</th>
                        <th class="p-4 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700">
                    @forelse($registrations as $reg)
                    <tr class="border-b hover:bg-indigo-50/30 transition group">
                        <td class="p-4">
                            <div class="font-bold text-slate-900">{{ $reg->first_name }} {{ $reg->last_name }}</div>
                            <span class="text-[10px] px-2 py-0.5 rounded bg-indigo-100 text-indigo-700 font-bold uppercase tracking-tighter">
                                {{ $reg->type }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="font-medium text-slate-800">{{ $reg->unit_type }} {{ $reg->unit_no }}</div>
                            <div class="text-xs text-slate-500">{{ $reg->building_name ?? $reg->property_title }}</div>
                        </td>
                        <td class="p-4">
                            <div class="font-mono text-[11px] flex flex-col gap-1">
                                <span class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 border border-slate-200">PAN: {{ $reg->pan }}</span>
                                <span class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-600 border border-slate-200">AAD: {{ $reg->aadhaar }}</span>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-1">
                                📞 <span class="font-medium">{{ $reg->mobile }}</span>
                            </div>
                            <div class="text-xs text-indigo-500 truncate max-w-[150px]">{{ $reg->email }}</div>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span>
                                Verified
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('crm.eregistrations.show', $reg->id) }}" class="text-indigo-600 hover:text-indigo-900" title="View Detail">👁️</a>
                                <a href="{{ route('crm.eregistrations.edit', $reg->id) }}" class="text-blue-600 hover:text-blue-800" title="Edit">✏️</a>
                                <button class="text-red-400 hover:text-red-600" title="Delete">🗑️</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <span class="text-4xl mb-2">📁</span>
                                <p class="italic">No linked registration records found for this view.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($registrations->hasPages())
        <div class="p-4 bg-slate-50 border-t">
            {{ $registrations->links() }}
        </div>
        @endif
    </div>
</body>