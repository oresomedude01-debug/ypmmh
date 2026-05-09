<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Post;

class NewBlogPostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $post;
    protected $isUpdate;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post, $isUpdate = false)
    {
        $this->post = $post;
        $this->isUpdate = $isUpdate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = ucfirst($this->post->status);
        $action = $this->isUpdate ? 'updated' : 'created';
        
        return (new MailMessage)
            ->subject("Blog Notification: Post {$status}")
            ->greeting("Hello Admin!")
            ->line("A blog post titled '{$this->post->title}' has been {$action} and set to **{$status}**.")
            ->line("Author: " . ($this->post->author->name ?? 'System'))
            ->action('View Post Management', route('admin.blogs.index'))
            ->line('Thank you for managing our platform contents!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'post_id' => $this->post->id,
            'title' => $this->post->title,
            'status' => $this->post->status,
            'author' => $this->post->author->name ?? 'System',
            'type' => 'blog_post',
            'message' => "A new blog post '{$this->post->title}' has been " . ($this->isUpdate ? 'updated' : 'published') . "."
        ];
    }
}
