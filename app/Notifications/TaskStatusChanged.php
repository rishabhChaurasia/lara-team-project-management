<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Task $task,
        public string $oldStatus,
        public string $newStatus,
        public User $changedBy
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
            ->subject('Task Status Updated: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->changedBy->name . ' changed the status of a task you\'re involved with.')
            ->line('**Task:** ' . $this->task->title)
            ->line('**Status Changed:** ' . ucfirst($this->oldStatus) . ' → ' . ucfirst($this->newStatus))
            ->action('View Task', route('projects.tasks.show', $this->task))
            ->line('Stay updated with your tasks!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'changed_by' => $this->changedBy->name,
            'changed_by_id' => $this->changedBy->id,
            'project_id' => $this->task->project_id,
            'message' => $this->changedBy->name . ' changed task status: ' . $this->task->title . ' (' . $this->oldStatus . ' → ' . $this->newStatus . ')',
            'action_url' => route('projects.tasks.show', $this->task),
        ];
    }
}
