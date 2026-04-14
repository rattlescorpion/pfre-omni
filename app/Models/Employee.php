<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'emp_no', 'user_id', 'first_name', 'last_name', 'email', 'phone',
        'dob', 'gender', 'blood_group', 'marital_status',
        'address', 'city', 'state', 'pincode',
        'aadhar_no', 'pan_no', 'uan_no', 'esic_no',
        'department', 'designation', 'grade', 'reporting_to',
        'joining_date', 'confirmation_date', 'exit_date', 'exit_reason',
        'employment_type', 'work_location',
        'bank_name', 'bank_account_no', 'bank_ifsc',
        'status', 'photo_path', 'notes',
    ];

    protected $casts = [
        'dob'               => 'date',
        'joining_date'      => 'date',
        'confirmation_date' => 'date',
        'exit_date'         => 'date',
    ];

    protected $hidden = ['aadhar_no', 'pan_no', 'uan_no', 'bank_account_no'];

    // ── Relationships ──────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reportingTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reporting_to');
    }

    public function salaryStructure(): HasOne
    {
        return $this->hasOne(SalaryStructure::class)->latestOfMany();
    }

    public function payrollEntries(): HasMany
    {
        return $this->hasMany(PayrollEntry::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(EmployeeBenefit::class);
    }

    // ── Helpers ────────────────────────────────────────────
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isOnProbation(): bool
    {
        return $this->confirmation_date === null
            || $this->confirmation_date->isFuture();
    }

    public function yearsOfService(): float
    {
        return round($this->joining_date->diffInDays(now()) / 365, 2);
    }
}