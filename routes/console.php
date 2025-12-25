<?php

use App\Models\Task;
use App\Notifications\DeadlineApproaching;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Check for approaching deadlines daily at 9 AM
Schedule::call(function () {
    $tomorrow = now()->addDay();

    // Find all tasks due tomorrow that are not completed
    Task::whereNotNull('assigned_to')
        ->whereDate('due_date', $tomorrow->toDateString())
        ->whereNotIn('status', ['completed'])
        ->with('assignee')
        ->get()
        ->each(function ($task) {
            if ($task->assignee) {
                $task->assignee->notify(new DeadlineApproaching($task));
            }
        });
})->dailyAt('09:00')->name('deadline-reminders')->description('Send deadline approaching notifications');

