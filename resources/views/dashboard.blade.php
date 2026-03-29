<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Property Finder Omni</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-10 font-sans">

    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-slate-800">Property Finder Real Estate</h1>
            <p class="text-lg text-slate-500 mt-2">Welcome to the Omni Platform Dashboard!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-blue-500">
                <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Active Leads</h2>
                <p class="text-5xl font-black text-blue-600 mt-2">{{ $kpis['active_leads'] }}</p>
                <p class="text-sm text-slate-500 mt-4">Across all Mumbai locations</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-red-500">
                <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Pending Rent</h2>
                <p class="text-5xl font-black text-red-600 mt-2">{{ $kpis['pending_rent'] }}</p>
                <p class="text-sm text-slate-500 mt-4">Requires immediate follow-up</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-amber-500">
                <h2 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Open Tickets</h2>
                <p class="text-5xl font-black text-amber-500 mt-2">{{ $kpis['open_tickets'] }}</p>
                <p class="text-sm text-slate-500 mt-4">Facility & Support requests</p>
            </div>

        </div>
    </div>

</body>
</html>