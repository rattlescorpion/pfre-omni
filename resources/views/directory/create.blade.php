@extends('layouts.app')

@section('title', 'Add 360° Record')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div>
        <a href="{{ route('directory.index') }}" class="text-sm font-semibold text-blue-700 dark:text-blue-300">← Back to Directory</a>
        <h1 class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">Add Person or Company</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Communication details are stored separately and can be reused.</p>
    </div>

    <form method="POST" action="{{ route('directory.store') }}" class="space-y-5 rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Record Type</label>
            <select name="entity_type" id="entity_type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white" required>
                <option value="person" @selected(old('entity_type', $type) === 'person')>Person</option>
                <option value="company" @selected(old('entity_type', $type) === 'company')>Company</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-semibold text-gray-700 dark:text-gray-200">Display Name *</label>
            <input name="display_name" value="{{ old('display_name') }}" required maxlength="190" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            @error('display_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div id="person_fields" class="grid gap-4 sm:grid-cols-3">
            <div><label class="mb-1 block text-sm font-medium dark:text-gray-200">First Name</label><input name="first_name" value="{{ old('first_name') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
            <div><label class="mb-1 block text-sm font-medium dark:text-gray-200">Middle Name</label><input name="middle_name" value="{{ old('middle_name') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
            <div><label class="mb-1 block text-sm font-medium dark:text-gray-200">Last Name</label><input name="last_name" value="{{ old('last_name') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
        </div>
        <div id="company_fields" class="grid gap-4 sm:grid-cols-2">
            <div><label class="mb-1 block text-sm font-medium dark:text-gray-200">Legal Name</label><input name="legal_name" value="{{ old('legal_name') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
            <div><label class="mb-1 block text-sm font-medium dark:text-gray-200">Trading Name</label><input name="trading_name" value="{{ old('trading_name') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
            <div><label class="mb-1 block text-sm font-medium dark:text-gray-200">Organisation Type</label><input name="organisation_type" placeholder="Private Limited, LLP, Partnership..." value="{{ old('organisation_type') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
            <div><label class="mb-1 block text-sm font-medium dark:text-gray-200">Website</label><input type="url" name="website" value="{{ old('website') }}" placeholder="https://" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div><label class="mb-1 block text-sm font-medium dark:text-gray-200">Mobile Number</label><input name="mobile" value="{{ old('mobile') }}" maxlength="32" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
            <div><label class="mb-1 block text-sm font-medium dark:text-gray-200">Email Address</label><input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white"></div>
        </div>
        <div><label class="mb-1 block text-sm font-medium dark:text-gray-200">Notes</label><textarea name="notes" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white">{{ old('notes') }}</textarea></div>
        <div class="flex justify-end gap-3"><a href="{{ route('directory.index') }}" class="rounded-lg border border-gray-300 px-4 py-2.5 font-semibold dark:border-gray-600 dark:text-white">Cancel</a><button class="rounded-lg bg-blue-700 px-5 py-2.5 font-semibold text-white hover:bg-blue-800">Save Record</button></div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const type = document.getElementById('entity_type');
    const person = document.getElementById('person_fields');
    const company = document.getElementById('company_fields');
    function updateFields() {
        person.hidden = type.value !== 'person';
        company.hidden = type.value !== 'company';
    }
    type.addEventListener('change', updateFields);
    updateFields();
});
</script>
@endpush
