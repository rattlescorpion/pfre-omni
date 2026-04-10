<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Crm\LeadService;
use Illuminate\Http\Request;

final class LeadController extends Controller
{
    public function __construct(
        private readonly LeadService $leadService
    ) {}

    // 1. This method shows the visual HTML form
    public function create()
    {
        return view('leads.create');
    }

    // 2. This method catches the data when the user clicks "Submit"
    public function store(Request $request)
    {
        // Check to make sure the user didn't leave required fields blank
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'required|string|max:15',
        ]);

        // We use a dummy ID of 1 since we haven't built the login screen yet
        $dummyUserId = 1;

        // Hand the validated data over to the service layer
        $this->leadService->create($validated, $dummyUserId);

        // Send the user back to the dashboard so they can see the counter go up!
        return redirect('/dashboard');
    }
}