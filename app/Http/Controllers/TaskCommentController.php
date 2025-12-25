<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Notifications\CommentAdded;
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

        $comment = TaskComment::create([
            ...$validated,
            'user_id' => auth()->id(),
            'task_id' => $task->id
        ]);

        // Send notification to task assignee (if not the commenter)
        if ($task->assignee && $task->assignee->id !== auth()->id()) {
            $task->assignee->notify(new CommentAdded($comment, $task));
        }

        // Send notification to task creator (if not the commenter and not the assignee)
        if ($task->creator->id !== auth()->id() && $task->creator->id !== $task->assigned_to) {
            $task->creator->notify(new CommentAdded($comment, $task));
        }

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
