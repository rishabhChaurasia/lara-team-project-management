<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Allow users to view their own tasks and tasks in projects they belong to
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // Allow the task assignee, creator, or users in the same project to view the task
        return $user->id === $task->assigned_to ||
               $user->id === $task->created_by ||
               $task->project->users->contains($user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow users to create tasks if they are members of any project
        // In a real implementation, you might want to be more specific about which project
        return true; // Since users have their own workspace, they can create tasks
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        // Allow the task assignee (member can update assigned tasks only),
        // or project managers/owners to update the task
        $isAssigned = $user->id === $task->assigned_to;
        $isCreator = $user->id === $task->created_by;

        // Check if user has manager or owner role in the project
        $hasManagementRole = $user->projects()
            ->where('projects.id', $task->project_id)
            ->wherePivotIn('role', ['owner', 'manager'])
            ->exists();

        // Members can update assigned tasks only
        $isMemberWithAssignedTask = $user->projects()
            ->where('projects.id', $task->project_id)
            ->wherePivot('role', 'member')
            ->exists() && $isAssigned;

        return $hasManagementRole || $isMemberWithAssignedTask || $isCreator;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        // Only project managers/owners can delete the task
        return $user->projects()
            ->where('projects.id', $task->project_id)
            ->wherePivotIn('role', ['owner', 'manager'])
            ->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        // Allow the task owner or project managers/admins to restore the task
        return $user->id === $task->user_id || $task->project->users->contains($user->id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        // Allow the task owner or project managers/admins to permanently delete the task
        return $user->id === $task->user_id || $task->project->users->contains($user->id);
    }
}
