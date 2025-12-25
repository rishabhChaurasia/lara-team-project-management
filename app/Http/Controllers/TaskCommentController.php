<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{

    use AuthorizesRequests;

    public function store(Request $request, Task $task)
    {
        // Check if user is a member of the task's project


        if (!$task->project->members()->where('users.id', auth()->id())->exists()) {
            abort(403, 'You must be a project member to comment on tasks.');
        }

        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        TaskComment::create([
            ...$validated,
            'user_id' => auth()->id(),
            'task_id' => $task->id
        ]);

        return back()->with('success', 'Comment added successfully!');
    }

    public function update(Request $request, TaskComment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $comment->update($validated);

        return back()->with('success', 'Comment updated successfully!');
    }

    public function destroy(TaskComment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}
