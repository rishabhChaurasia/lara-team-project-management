<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{

    use AuthorizesRequests;

    public function index(Project $project): View
    {
        $this->authorize('viewAny', Task::class);

        $tasks = $project->tasks()->with(['user', 'project'])->get();

        return view('tasks.index', compact('tasks', 'project'));
    }

    public function create(Project $project): View
    {
        $this->authorize('create', Task::class);

        return view('tasks.create', compact('project'));
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('create', Task::class);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'status'      => 'nullable|string|max:50',
        ]);

        $project->tasks()->create([
             ...$validated,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('projects.tasks.index', $project)->with('success', 'Task created successfully.');
    }

    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date'    => 'nullable|date',
            'status'      => 'nullable|string|max:50',
        ]);

        $task->update($validated);

        return redirect()->route('projects.tasks.index', $task->project)->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);

        $project = $task->project;
        $task->delete();

        return redirect()->route('projects.tasks.index', $project)->with('success', 'Task deleted successfully.');
    }

    public function assign(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $task->update($validated);

        return response()->json(['message' => 'Task assigned successfully.']);
    }

    public function status(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => 'required|string|max:50',
        ]);

        $task->update($validated);

        return response()->json(['message' => 'Status updated successfully.']);
    }
}
