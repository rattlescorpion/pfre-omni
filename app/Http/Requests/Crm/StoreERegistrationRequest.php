<?php declare(strict_types=1);

namespace App\Http\Requests\Crm;

use Illuminate\Foundation\Http\FormRequest;

final class StoreERegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lead_id' => 'required|exists:leads,id',
            'property_id' => 'required|exists:properties,id',
            'created_by' => 'required|exists:employees,id',
            'registration_number' => 'nullable|string|max:100|unique:e_registrations,registration_number',
            'stamp_duty_amount' => 'required|numeric|min:0',
            'registration_fees' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'payment_status' => 'nullable|string|in:pending,paid,failed',
            'document_status' => 'nullable|string|in:pending,submitted,verified,rejected',
            'gstin' => 'nullable|string|max:15|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            'pan_number' => 'nullable|string|max:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'aadhaar_number' => 'nullable|string|max:12|regex:/^[0-9]{12}$/',
            'bank_reference' => 'nullable|string|max:100',
            'transaction_id' => 'nullable|string|max:100',
            'registration_date' => 'nullable|date|before_or_equal:today',
            'stamp_duty_paid' => 'nullable|boolean',
            'registration_completed' => 'nullable|boolean',
            'document_submitted' => 'nullable|boolean',
            'verification_status' => 'nullable|string|in:pending,verified,rejected',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
