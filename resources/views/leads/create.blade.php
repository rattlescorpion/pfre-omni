<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Lead - Property Finder Omni</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 p-10 font-sans">

    <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border-t-4 border-blue-500">
        
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-slate-800">Add New Lead</h1>
            <p class="text-sm text-slate-500">Enter the client's details to add them to the CRM.</p>
        </div>

        <form action="/leads" method="POST" class="space-y-4">
            @csrf 

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Full Name *</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. John Doe">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Phone Number *</label>
                <input type="text" name="phone" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. 9876543210">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Intent</label>
                <select name="intent" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="buy">Buy</option>
                    <option value="rent">Rent</option>
                    <option value="sell">Sell</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Current Stage</label>
                <select name="stage" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="new">New</option>
                    <option value="contacted">Contacted</option>
                    <option value="visit_scheduled">Visit Scheduled</option>
                </select>
            </div>

            <div class="pt-4 flex justify-between items-center">
                <a href="/dashboard" class="text-sm text-slate-500 hover:text-slate-800">Cancel & Go Back</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-200">
                    Save Lead
                </button>
            </div>
        </form>

    </div>

</body>
</html>