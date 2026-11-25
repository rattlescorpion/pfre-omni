<?php declare(strict_types=1);

namespace App\\Http\\Requests\\Crm;

use Illuminate\\Foundation\\Http\\FormRequest;

final class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'unit_number' => ['required', 'string', 'max:100'],
            'carpet_area' => ['required', 'numeric', 'gt:0'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'in:Residential,Commercial,Plot'],
            'status' => ['nullable', 'in:Available,Blocked,Sold'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:100'],
        ];
    }
}
