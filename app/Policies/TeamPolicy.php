<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Team $team): bool
    {
        return $user->projects()->where('projects.id', $team->project_id)
            ->wherePivotIn('role', ['owner', 'manager', 'member'])
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        return $user->projects()->where('projects.id', $project->id)
            ->wherePivotIn('role', ['owner', 'manager'])
            ->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        return $user->projects()->where('projects.id', $team->project_id)
            ->wherePivotIn('role', ['owner', 'manager'])
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        return $user->projects()->where('projects.id', $team->project_id)
            ->wherePivotIn('role', ['owner', 'manager'])
            ->exists();
    }

    /**
     * Determine whether the user can add members to the team.
     */
    public function addMember(User $user, Team $team): bool
    {
        return $user->projects()->where('projects.id', $team->project_id)
            ->wherePivotIn('role', ['owner', 'manager'])
            ->exists();
    }

    /**
     * Determine whether the user can remove members from the team.
     */
    public function removeMember(User $user, Team $team, User $member): bool
    {
        // Managers and owners can remove members
        $canModify = $user->projects()->where('projects.id', $team->project_id)
            ->wherePivotIn('role', ['owner', 'manager'])
            ->exists();

        // Cannot remove an owner from their own project
        $isOwner = $member->projects()->where('projects.id', $team->project_id)
            ->wherePivot('role', 'owner')
            ->exists();

        return $canModify && !$isOwner;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Team $team): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Team $team): bool
    {
        return false;
    }
}
