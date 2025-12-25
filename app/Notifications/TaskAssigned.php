<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Task $task,
        public User $assigner
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
        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->assigner->name . ' has assigned you a new task.')
            ->line('**Task:** ' . $this->task->title)
            ->line('**Priority:** ' . ucfirst($this->task->priority))
            ->line('**Due Date:** ' . ($this->task->due_date ? $this->task->due_date->format('M d, Y') : 'No deadline'))
            ->action('View Task', route('projects.tasks.show', $this->task))
            ->line('Thank you for your contribution!');
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
            'assigned_by' => $this->assigner->name,
            'assigned_by_id' => $this->assigner->id,
            'project_id' => $this->task->project_id,
            'message' => $this->assigner->name . ' assigned you to: ' . $this->task->title,
            'action_url' => route('projects.tasks.show', $this->task),
        ];
    }
}

