<?php declare(strict_types=1);

namespace App\Http\Requests\Crm;

use Illuminate\Foundation\Http\FormRequest;

final class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:apartment,shop,office,plot,villa',
            'price' => 'nullable|numeric|min:0',
            'address' => 'required|string|max:500',
            'locality' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:10',
            'area_sqft' => 'nullable|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'parking' => 'nullable|boolean',
            'furnished' => 'nullable|boolean',
            'status' => 'nullable|string|in:available,sold,rented,under_contract',
            'description' => 'nullable|string|max:1000',
            'owner_name' => 'nullable|string|max:255',
            'owner_phone' => 'nullable|string|max:15',
            'owner_email' => 'nullable|email|max:255',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
        ];
    }
}
