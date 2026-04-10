<?php declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Grant all permissions to a technical 'super-admin' automatically.
     */
    public function before(User $user, $ability): ?bool
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return null; // Fall through to specific methods
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // 1. Own profile OR 2. Admin/Manager of the SAME tenant
        return $user->id === $model->id || 
               ($user->hasAnyRole(['admin', 'manager']) && $user->tenant_id === $model->tenant_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Prevent editing people in other companies even if the user is an admin
        if ($user->tenant_id !== $model->tenant_id) {
            return false;
        }

        return $user->id === $model->id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Admins can delete users in their own tenant, but cannot delete themselves
        return $user->hasRole('admin') && 
               $user->tenant_id === $model->tenant_id && 
               $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasRole('admin') && $user->tenant_id === $model->tenant_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Usually restricted to super-admins only for audit trail safety
        return $user->hasRole('super-admin');
    }
}