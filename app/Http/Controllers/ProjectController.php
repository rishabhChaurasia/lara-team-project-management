<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('viewAny', Project::class);
        
        $projects = auth()->user()->projects()->with('creator')->latest()->paginate(15);
        
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);
        
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Project::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,archived',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project = Project::create([
            ...$validated,
            'created_by' => auth()->id(),
        ]);

        // Automatically add creator as owner
        $project->members()->attach(auth()->id(), ['role' => 'owner']);

        return redirect()->route('projects.show', $project)->with('success', 'Workspace created successfully!');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        
        $project->load(['members', 'teams', 'tasks']);
        
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,archived',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Workspace updated successfully!');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Workspace deleted successfully!');
    }

    public function members(Project $project)
    {
        $this->authorize('view', $project);
        
        $members = $project->members()->withPivot('role')->get();
        
        return view('projects.members', compact('project', 'members'));
    }

    public function addMember(Request $request, Project $project)
    {
        $this->authorize('addMember', $project);
        
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:manager,member',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($project->members()->where('users.id', $user->id)->exists()) {
            return back()->with('error', 'User is already a member!');
        }

        $project->members()->attach($user->id, ['role' => $validated['role']]);

        return back()->with('success', 'Member added successfully!');
    }

    public function removeMember(Project $project, $userId)
    {
        $user = User::findOrFail($userId);
        
        $this->authorize('removeMember', [$project, $user]);
        
        $project->members()->detach($userId);

        return back()->with('success', 'Member removed successfully!');
    }
}
