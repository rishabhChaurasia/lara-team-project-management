<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentAdded extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TaskComment $comment,
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
        return (new MailMessage)
            ->subject('New Comment on Task: ' . $this->task->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->comment->user->name . ' added a comment on a task you\'re involved with.')
            ->line('**Task:** ' . $this->task->title)
            ->line('**Comment:** ' . \Illuminate\Support\Str::limit($this->comment->content, 150))
            ->action('View Task & Comment', route('projects.tasks.show', $this->task))
            ->line('Join the discussion!');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'comment_id' => $this->comment->id,
            'comment_excerpt' => \Illuminate\Support\Str::limit($this->comment->content, 100),
            'commented_by' => $this->comment->user->name,
            'commented_by_id' => $this->comment->user_id,
            'project_id' => $this->task->project_id,
            'message' => $this->comment->user->name . ' commented on: ' . $this->task->title,
            'action_url' => route('projects.tasks.show', $this->task),
        ];
    }
}
