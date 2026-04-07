<?php namespace App\Http\Resources\Crm;

use Illuminate\Http\Resources\Json\JsonResource;

class ERegistrationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'party_name' => "{$this->salutation} {$this->first_name} {$this->last_name}",
            'type' => $this->type,
            'contact' => [
                'mobile' => $this->mobile,
                'email' => $this->email,
            ],
            'property' => [
                'title' => $this->property_title ?? 'N/A',
                'full_address' => "{$this->unit_no}, {$this->building_name}, {$this->city}",
            ],
            'kyc_status' => ($this->pan && $this->aadhaar) ? 'Verified' : 'Pending',
            'created_at' => $this->created_at->format('d-m-Y H:i'),
        ];
    }
}