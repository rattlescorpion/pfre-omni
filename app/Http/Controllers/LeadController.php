<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Crm\LeadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class LeadController extends Controller
{
    public function __construct(
        private readonly LeadService $leadService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Lead pipeline loaded successfully.'
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        // In a real scenario, the $userId would come from the logged-in user: auth()->id()
        $dummyUserId = 1; 
        
        $lead = $this->leadService->create($request->all(), $dummyUserId);

        return response()->json([
            'status' => 'success',
            'message' => 'Lead created successfully.',
            'data' => $lead
        ], 201);
    }
}