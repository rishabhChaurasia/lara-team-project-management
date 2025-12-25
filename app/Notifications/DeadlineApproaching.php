<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeadlineApproaching extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Task $task
    ) {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $hoursLeft = now()->diffInHours($this->task->due_date);

        return (new MailMessage)
            ->subject('⚠️ Deadline Approaching: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a friendly reminder that a task deadline is approaching.')
            ->line('**Task:** ' . $this->task->title)
            ->line('**Due Date:** ' . $this->task->due_date->format('M d, Y g:i A'))
            ->line('**Time Remaining:** ~' . $hoursLeft . ' hours')
            ->line('**Priority:** ' . ucfirst($this->task->priority))
            ->action('View Task', route('projects.tasks.show', $this->task))
            ->line('Make sure to complete this task on time!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'task_priority' => $this->task->priority,
            'due_date' => $this->task->due_date->format('Y-m-d H:i:s'),
            'hours_remaining' => now()->diffInHours($this->task->due_date),
            'project_id' => $this->task->project_id,
            'message' => '⚠️ Task deadline approaching in 24h: ' . $this->task->title,
            'action_url' => route('projects.tasks.show', $this->task),
        ];
    }
}
