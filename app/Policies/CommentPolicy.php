<?php

namespace App\Policies;

use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any authenticated user can view comments
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TaskComment $taskComment): bool
    {
        // Any project member can view comments
        return $user->projects()
            ->where('projects.id', $taskComment->task->project_id)
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any project member can create comments
        // This will be checked in the controller with the specific task/project
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TaskComment $taskComment): bool
    {
        // Only comment author can update, and only within 15 minutes
        if ($user->id !== $taskComment->user_id) {
            return false;
        }

        // Check if comment was created within last 15 minutes
        $fifteenMinutesAgo = now()->subMinutes(15);
        return $taskComment->created_at->greaterThan($fifteenMinutesAgo);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TaskComment $taskComment): bool
    {
        // Comment author can always delete their own comment
        if ($user->id === $taskComment->user_id) {
            return true;
        }

        // Project owners and managers can delete any comment
        return $user->projects()
            ->where('projects.id', $taskComment->task->project_id)
            ->wherePivotIn('role', ['owner', 'manager'])
            ->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TaskComment $taskComment): bool
    {
        // Only project owners can restore deleted comments
        return $user->projects()
            ->where('projects.id', $taskComment->task->project_id)
            ->wherePivot('role', 'owner')
            ->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TaskComment $taskComment): bool
    {
        // Only project owners can permanently delete comments
        return $user->projects()
            ->where('projects.id', $taskComment->task->project_id)
            ->wherePivot('role', 'owner')
            ->exists();
    }
}
