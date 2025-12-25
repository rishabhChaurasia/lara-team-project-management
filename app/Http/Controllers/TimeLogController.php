<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TimeLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TimeLogController extends Controller
{
    use AuthorizesRequests;

    public function index(Task $task)
    {
        $this->authorize('view', $task);

        $timeLogs = $task->timeLogs()
            ->with('user')
            ->latest()
            ->get();

        $totalMinutes = $timeLogs->sum('duration_minutes');
        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;

        return view('time-logs.index', compact('task', 'timeLogs', 'totalHours', 'remainingMinutes'));
    }

    public function start(Request $request, Task $task)
    {
        $this->authorize('view', $task);

        $activeTimer = TimeLog::where('user_id', auth()->id())
            ->whereNull('ended_at')
            ->first();

        if ($activeTimer) {
            return back()->with('error', 'You already have an active timer running. Please stop it first.');
        }

        $validated = $request->validate([
            'description' => 'nullable|string|max:500'
        ]);

        TimeLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'started_at' => now(),
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'Timer started successfully!');
    }

    public function stop(Request $request, Task $task)
    {
        $this->authorize('view', $task);

        $timeLog = TimeLog::where('task_id', $task->id)
            ->where('user_id', auth()->id())
            ->whereNull('ended_at')
            ->first();

        if (!$timeLog) {
            return back()->with('error', 'No active timer found for this task.');
        }

        $endedAt = now();
        $durationMinutes = $timeLog->started_at->diffInMinutes($endedAt);

        $timeLog->update([
            'ended_at' => $endedAt,
            'duration_minutes' => $durationMinutes,
        ]);

        $hours = floor($durationMinutes / 60);
        $minutes = $durationMinutes % 60;
        $timeFormatted = $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";

        return back()->with('success', "Timer stopped! Duration: {$timeFormatted}");
    }
}
