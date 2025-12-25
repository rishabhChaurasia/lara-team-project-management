<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Invitation;
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
            'email' => 'required|email',
            'role' => 'required|in:manager,member',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user) {
            // User exists, add them directly to the project
            if ($project->members()->where('users.id', $user->id)->exists()) {
                return back()->with('error', 'User is already a member!');
            }

            $project->members()->attach($user->id, ['role' => $validated['role']]);

            return back()->with('success', 'Member added successfully!');
        } else {
            // User doesn't exist, create an invitation
            $existingInvitation = Invitation::where('email', $validated['email'])
                ->where('project_id', $project->id)
                ->whereNull('accepted_at')
                ->first();

            if ($existingInvitation) {
                return back()->with('error', 'An invitation has already been sent to this email!');
            }

            // Create invitation
            $token = bin2hex(random_bytes(32)); // Generate unique token
            $expiresAt = now()->addDays(7); // Invitation expires in 7 days

            Invitation::create([
                'email' => $validated['email'],
                'project_id' => $project->id,
                'invited_by' => auth()->id(),
                'role' => $validated['role'],
                'token' => $token,
                'expires_at' => $expiresAt,
            ]);

            // Here you would typically send an email with the invitation link
            // For now, just show a success message
            // Mail::to($validated['email'])->send(new InvitationMail($invitation));

            return back()->with('success', 'Invitation sent successfully! The user will be able to join once they register.');
        }
    }

    public function removeMember(Project $project, $userId)
    {
        $user = User::findOrFail($userId);

        $this->authorize('removeMember', [$project, $user]);

        $project->members()->detach($userId);

        return back()->with('success', 'Member removed successfully!');
    }

    public function showInvitation($token)
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        return view('invitations.show', compact('invitation'));
    }

    public function acceptInvitation(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to accept the invitation.');
        }

        // Check if the authenticated user's email matches the invitation email
        if (auth()->user()->email !== $invitation->email) {
            return back()->with('error', 'You are not authorized to accept this invitation.');
        }

        // Check if user is already a member of the project
        if ($invitation->project->members()->where('users.id', auth()->id())->exists()) {
            return redirect()->route('projects.show', $invitation->project)->with('error', 'You are already a member of this project.');
        }

        // Add user to the project with the specified role
        $invitation->project->members()->attach(auth()->id(), ['role' => $invitation->role]);

        // Mark the invitation as accepted
        $invitation->update(['accepted_at' => now()]);

        return redirect()->route('projects.show', $invitation->project)->with('success', 'You have successfully joined the project!');
    }
}
