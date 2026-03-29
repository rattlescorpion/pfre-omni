<body class="bg-slate-100 p-6 md:p-10">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-blue-600 p-6">
            <h2 class="text-2xl font-bold text-white">Add New Property</h2>
            <p class="text-blue-100">Enter the details for the new listing in PFRE OMNI</p>
        </div>

        <form action="/properties" method="POST" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Property Title</label>
                <input type="text" name="title" required placeholder="e.g. 3BHK Luxury Apartment - Bandra West" 
                       class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Property Type</label>
                <select name="type" class="w-full px-4 py-3 rounded-lg border border-slate-300 outline-none">
                    <option value="apartment">Apartment / Flat</option>
                    <option value="shop">Commercial Shop</option>
                    <option value="office">Office Space</option>
                    <option value="plot">Land / Plot</option>
                    <option value="villa">Villa / Bungalow</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Price (₹)</label>
                <input type="number" name="price" placeholder="5000000" 
                       class="w-full px-4 py-3 rounded-lg border border-slate-300 outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Location / Address</label>
                <textarea name="location" rows="3" placeholder="Full address or locality details..." 
                          class="w-full px-4 py-3 rounded-lg border border-slate-300 outline-none"></textarea>
            </div>

            <div class="md:col-span-2 flex justify-end gap-4 mt-4">
                <a href="/properties" class="px-6 py-3 text-slate-600 font-semibold hover:bg-slate-50 rounded-lg transition">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-md transition">
                    Save Property
                </button>
            </div>
        </form>
    </div>
</body>