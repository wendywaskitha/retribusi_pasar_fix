<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TargetRetribusi;
use Illuminate\Auth\Access\HandlesAuthorization;

class TargetRetribusiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_target::retribusi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TargetRetribusi $targetRetribusi): bool
    {
        return $user->can('view_target::retribusi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_target::retribusi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TargetRetribusi $targetRetribusi): bool
    {
        return $user->can('update_target::retribusi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TargetRetribusi $targetRetribusi): bool
    {
        return $user->can('delete_target::retribusi');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_target::retribusi');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, TargetRetribusi $targetRetribusi): bool
    {
        return $user->can('force_delete_target::retribusi');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_target::retribusi');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, TargetRetribusi $targetRetribusi): bool
    {
        return $user->can('restore_target::retribusi');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_target::retribusi');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, TargetRetribusi $targetRetribusi): bool
    {
        return $user->can('replicate_target::retribusi');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_target::retribusi');
    }
}
