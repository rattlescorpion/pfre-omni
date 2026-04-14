<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\{HasMany, BelongsToMany, HasOne};

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'avatar',
        'two_factor_secret', 'two_factor_enabled', 'two_factor_confirmed', 'backup_codes',
        'status', 'failed_logins', 'locked_until', 'last_login_at', 'last_login_ip',
        'language', 'timezone', 'date_format', 'default_dashboard', 'notification_preferences',
        'email_verified_at',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'backup_codes',
    ];

    protected $casts = [
        'email_verified_at'        => 'datetime',
        'password'                 => 'hashed', // Modern Laravel 10/11 standard
        'locked_until'             => 'datetime',
        'last_login_at'            => 'datetime',
        'two_factor_enabled'       => 'boolean',
        'two_factor_confirmed'     => 'boolean',
        'backup_codes'             => 'array',
        'notification_preferences' => 'array',
    ];

    // ── Relationships ──────────────────────────────────────
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permissions')
                    ->withPivot('is_granted');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    // ── Helpers ────────────────────────────────────────────
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission(string $permission): bool
    {
        // Check user-specific override first
        $userPerm = $this->permissions()->where('name', $permission)->first();
        if ($userPerm) {
            return (bool) $userPerm->pivot->is_granted;
        }
        // Then check via roles
        return $this->roles()
            ->whereHas('permissions', fn ($q) => $q->where('name', $permission))
            ->exists();
    }

    public function isLocked(): bool
    {
        if ($this->status === 'locked') {
            if ($this->locked_until && $this->locked_until->isPast()) {
                $this->update(['status' => 'active', 'failed_logins' => 0, 'locked_until' => null]);
                return false;
            }
            return true;
        }
        return false;
    }

    public function recordLogin(string $ip): void
    {
        $this->update([
            'last_login_at'   => now(),
            'last_login_ip'   => $ip,
            'failed_logins'   => 0,
            'locked_until'    => null,
        ]);
    }

    public function recordFailedLogin(): void
    {
        $maxAttempts = (int) config('auth.max_attempts', 5);
        $newCount    = $this->failed_logins + 1;
        $updates     = ['failed_logins' => $newCount];

        if ($newCount >= $maxAttempts) {
            $updates['status']       = 'locked';
            $updates['locked_until'] = now()->addMinutes(15);
        }

        $this->update($updates);
    }
}