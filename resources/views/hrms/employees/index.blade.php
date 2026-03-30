<body class="bg-slate-100 p-10">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-slate-800">Team Directory</h1>
            <a href="/employees/create" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                + Onboard Employee
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-4 text-sm font-semibold text-slate-600">Employee ID</th>
                        <th class="p-4 text-sm font-semibold text-slate-600">Name</th>
                        <th class="p-4 text-sm font-semibold text-slate-600">Department</th>
                        <th class="p-4 text-sm font-semibold text-slate-600">Status</th>
                        <th class="p-4 text-sm font-semibold text-slate-600">Joined Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="p-4 font-mono text-blue-600">{{ $employee->employee_no }}</td>
                        <td class="p-4 font-medium">{{ $employee->name }}</td>
                        <td class="p-4"><span class="px-2 py-1 bg-slate-100 rounded text-xs uppercase">{{ $employee->department }}</span></td>
                        <td class="p-4">
                            <span class="px-2 py-1 {{ $employee->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} rounded-full text-xs font-bold">
                                {{ ucfirst($employee->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-500">{{ date('d M Y', strtotime($employee->created_at)) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-slate-400 italic">No employees found. Time to grow the team!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>