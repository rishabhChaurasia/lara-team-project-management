<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Project;
use App\Models\ProjectMember;

class CreatePersonalWorkspace
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;

        // Create a personal workspace (project) for the user
        $workspace = Project::create([
            'name' => $user->name . "'s Workspace",
            'description' => $user->name . "'s personal workspace",
            'status' => 'active',
            'created_by' => $user->id,
        ]);

        // Add the user as the owner of their workspace
        ProjectMember::create([
            'project_id' => $workspace->id,
            'user_id' => $user->id,
            'role' => 'owner',
        ]);
    }
}
