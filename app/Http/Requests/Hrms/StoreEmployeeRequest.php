<?php declare(strict_types=1);

namespace App\Http\Requests\Hrms;

use Illuminate\Foundation\Http\FormRequest;

final class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'required|string|max:15',
            'employee_id' => 'required|string|unique:employees,employee_id',
            'department' => 'required|string|max:100',
            'designation' => 'required|string|max:100',
            'joining_date' => 'required|date|before_or_equal:today',
            'salary' => 'required|numeric|min:0',
            'status' => 'nullable|string|in:active,inactive,on_leave,resigned',
            'manager_id' => 'nullable|exists:employees,id',
            'address' => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|string|in:male,female,other',
            'qualification' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
        ];
    }
}
