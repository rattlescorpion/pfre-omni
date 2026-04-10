<?php declare(strict_types=1);

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Api\BaseApiController;
use App\Services\Crm\ERegistrationService;
use App\Http\Resources\Crm\ERegistrationResource;
use App\Http\Requests\Crm\StoreERegistrationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ERegistrationApiController extends BaseApiController
{
    /**
     * @param ERegistrationService $service
     */
    public function __construct(protected ERegistrationService $service) {}

    /**
     * @OA\Get(path="/api/v1/crm/registrations")
     */
    public function index(Request $request): JsonResponse
    {
        // Filters by project_id, status, or date range
        $data = $this->service->getAll(); 
        
        return $this->sendResponse(
            ERegistrationResource::collection($data), 
            'Registrations retrieved successfully.'
        );
    }

    /**
     * @OA\Post(path="/api/v1/crm/registrations")
     */
    public function store(StoreERegistrationRequest $request): JsonResponse
    {
        // StoreERegistrationRequest handles GST/PAN validation via IndiaHelpers
        $record = $this->service->create(
            $request->validated(), 
            auth()->id() ?? 1
        );
        
        return $this->sendResponse(
            new ERegistrationResource((object)$record), 
            'Registration created and audited successfully.', 
            201
        );
    }

    /**
     * Update Stamp Duty or Appointment status for E-Registration.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $record = $this->service->update($id, $request->except(['_token', '_method']), auth()->id());

        return $this->sendResponse(
            new ERegistrationResource((object)$record),
            'Registration record updated successfully.'
        );
    }

    /**
     * Fetch document verification status (e.g., Aadhaar eSign status).
     */
    public function verifyStatus(int $id): JsonResponse
    {
        $status = $this->service->checkVerificationStatus($id);
        
        return $this->sendResponse($status, 'Verification status synced.');
    }
}