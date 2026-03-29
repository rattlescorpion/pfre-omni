<body class="bg-slate-100 p-10">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Property Inventory</h1>
            <a href="/properties/create" class="bg-green-600 text-white px-4 py-2 rounded">+ Add Property</a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b">
                    <tr>
                        <th class="p-4">Title</th>
                        <th class="p-4">Type</th>
                        <th class="p-4">Price</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($properties as $property)
                    <tr class="border-b">
                        <td class="p-4">{{ $property->title }}</td>
                        <td class="p-4">{{ ucfirst($property->type) }}</td>
                        <td class="p-4">₹{{ number_format($property->price) }}</td>
                        <td class="p-4"><span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">Available</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>