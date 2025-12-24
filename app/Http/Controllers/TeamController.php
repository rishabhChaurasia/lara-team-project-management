<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    use AuthorizesRequests;

    public function index(Project $project)
    {
        $this->authorize('view', $project);

        $teams = $project->teams()->with('members')->latest()->paginate(15);

        return view('teams.index', compact('teams', 'project'));
    }

    public function create(Project $project)
    {
        $this->authorize('create', [Team::class, $project]);

        return view('teams.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorize('create', [Team::class, $project]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team = $project->teams()->create($validated);

        return redirect()->route('projects.teams.index', $project)->with('success', 'Team created successfully!');
    }

    public function show(Team $team)
    {
        $this->authorize('view', $team);

        $team->load(['project', 'members', 'tasks']);

        return view('teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $this->authorize('update', $team);

        return view('teams.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $this->authorize('update', $team);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $team->update($validated);

        return redirect()->route('teams.show', $team)->with('success', 'Team updated successfully!');
    }

    public function destroy(Team $team)
    {
        $this->authorize('delete', $team);

        $project = $team->project;
        $team->delete();

        return redirect()->route('projects.teams.index', $project)->with('success', 'Team deleted successfully!');
    }

    public function addMember(Request $request, Team $team)
    {
        $this->authorize('addMember', $team);

        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$team->project->members()->where('users.id', $user->id)->exists()) {
            return back()->with('error', 'User is not a member of this project!');
        }

        if ($team->members()->where('users.id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member of this team!');
        }

        $team->members()->attach($user->id);

        return back()->with('success', 'Member added to team successfully!');
    }

    public function removeMember(Team $team, $userId)
    {
        $user = User::findOrFail($userId);

        $this->authorize('removeMember', [$team, $user]);

        $team->members()->detach($userId);

        return back()->with('success', 'Member removed from team successfully!');
    }
}
