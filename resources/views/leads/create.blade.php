{{-- resources/views/leads/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Add New Lead')

@section('content')
<div x-data="{ step: 1 }">
    {{-- Breadcrumb --}}
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li><a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">Dashboard</a></li>
            <li><span class="mx-2 text-gray-400">/</span></li>
            <li><a href="{{ route('leads.index') }}" class="text-gray-500 hover:text-gray-700">Leads</a></li>
            <li><span class="mx-2 text-gray-400">/</span></li>
            <li class="text-primary-600 font-medium">Add New Lead</li>
        </ol>
    </nav>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Add New Lead</h1>

            {{-- Step Progress --}}
            <div class="flex mb-8">
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium"
                             :class="step >= 1 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-600'">1</div>
                        <div class="ml-2 text-sm" :class="step >= 1 ? 'text-primary-600 font-medium' : 'text-gray-500'">Basic Info</div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium"
                             :class="step >= 2 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-600'">2</div>
                        <div class="ml-2 text-sm" :class="step >= 2 ? 'text-primary-600 font-medium' : 'text-gray-500'">Requirements</div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium"
                             :class="step >= 3 ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-600'">3</div>
                        <div class="ml-2 text-sm" :class="step >= 3 ? 'text-primary-600 font-medium' : 'text-gray-500'">Confirmation</div>
                    </div>
                </div>
            </div>

            <form action="{{ route('leads.store') }}" method="POST">
                @csrf

                {{-- Step 1: Basic Information --}}
                <div x-show="step === 1">
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name *</label>
                            <input type="text" name="name" id="name"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                   required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number *</label>
                                <input type="tel" name="phone" id="phone"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                       required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" name="email" id="email"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="source" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lead Source</label>
                                <select name="source" id="source"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="">Select Source</option>
                                    <option value="website">Website</option>
                                    <option value="referral">Referral</option>
                                    <option value="walk_in">Walk-in</option>
                                    <option value="advertisement">Advertisement</option>
                                </select>
                            </div>

                            <div>
                                <label for="stage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Stage</label>
                                <select name="stage" id="stage"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <option value="new">New</option>
                                    <option value="contacted">Contacted</option>
                                    <option value="visit_scheduled">Visit Scheduled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Requirements --}}
                <div x-show="step === 2">
                    <div class="space-y-6">
                        <div>
                            <label for="intent" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Intent *</label>
                            <select name="intent" id="intent"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                    required>
                                <option value="">Select Intent</option>
                                <option value="buy">Buy</option>
                                <option value="rent">Rent</option>
                                <option value="sell">Sell</option>
                            </select>
                        </div>

                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Budget Range (₹)</label>
                            <div class="grid grid-cols-2 gap-4">
                                <input type="number" name="budget_min" placeholder="Min"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                <input type="number" name="budget_max" placeholder="Max"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </div>
                        </div>

                        <div>
                            <label for="preferred_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preferred Location</label>
                            <input type="text" name="preferred_location" id="preferred_location"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"></textarea>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Confirmation --}}
                <div x-show="step === 3">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Confirm Details</h3>
                        <p class="text-gray-600 dark:text-gray-400">Please review the information before saving the lead.</p>
                        {{-- A real summary could be added here using Alpine bindings --}}
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex justify-between mt-8">
                    <button type="button" @click="step--" x-show="step > 1"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Previous
                    </button>
                    <button type="button" @click="step++" x-show="step < 3"
                            class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                        Next
                    </button>
                    <button type="submit" x-show="step === 3"
                            class="px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                        Save Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection