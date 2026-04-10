<?php declare(strict_types=1);

namespace App\Http\Requests\Crm;

use Illuminate\Foundation\Http\FormRequest;

final class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'required|date|after_or_equal:today',
            'priority' => 'nullable|string|in:low,medium,high,urgent',
            'status' => 'nullable|string|in:pending,in_progress,completed,cancelled',
            'assigned_to' => 'nullable|exists:employees,id',
            'assigned_by' => 'nullable|exists:employees,id',
            'lead_id' => 'nullable|exists:leads,id',
            'property_id' => 'nullable|exists:properties,id',
            'reminder_sent' => 'nullable|boolean',
            'completed_at' => 'nullable|date',
        ];
    }
}
