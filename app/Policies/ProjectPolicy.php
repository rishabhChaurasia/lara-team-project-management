<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any authenticated user can view their projects
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // User can view if they are a member of the project
        return $user->projects()->where('projects.id', $project->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create a workspace
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // Only owner or manager can update
        return $user->projects()
            ->where('projects.id', $project->id)
            ->wherePivotIn('role', ['owner', 'manager'])
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // Only owner can delete workspace
        return $user->projects()
            ->where('projects.id', $project->id)
            ->wherePivot('role', 'owner')
            ->exists();
    }

    /**
     * Determine whether the user can add members.
     */
    public function addMember(User $user, Project $project): bool
    {
        // Only owner or manager can add members
        return $user->projects()
            ->where('projects.id', $project->id)
            ->wherePivotIn('role', ['owner', 'manager'])
            ->exists();
    }

    /**
     * Determine whether the user can remove members.
     */
    public function removeMember(User $user, Project $project, User $memberToRemove): bool
    {
        // Cannot remove owner
        $memberRole = $project->members()
            ->where('users.id', $memberToRemove->id)
            ->first()?->pivot->role;
        
        if ($memberRole === 'owner') {
            return false;
        }

        // Only owner or manager can remove members
        return $user->projects()
            ->where('projects.id', $project->id)
            ->wherePivotIn('role', ['owner', 'manager'])
            ->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        // Only owner can restore
        return $user->projects()
            ->where('projects.id', $project->id)
            ->wherePivot('role', 'owner')
            ->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        // Only owner can force delete
        return $user->projects()
            ->where('projects.id', $project->id)
            ->wherePivot('role', 'owner')
            ->exists();
    }
}
