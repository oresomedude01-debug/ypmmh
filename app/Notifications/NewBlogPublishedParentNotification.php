<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Post;

class NewBlogPublishedParentNotification extends Notification
{
    use Queueable;

    protected $post;

    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
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
        return (new MailMessage)
            ->subject("New Article: Guidance for Your Family Insights")
            ->greeting("Salaam Representative of Allah on Earth!")
            ->line("We've just published a new article on YPMMH that might interest you: '**{$this->post->title}**'.")
            ->line($this->post->excerpt ?? 'Read more about Islamic mentoring and child development.')
            ->action('Read Article', route('blog.show', $this->post->slug))
            ->line('Building a productive, purpose-driven generation together.');
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
            'slug' => $this->post->slug,
            'type' => 'blog_published',
            'icon' => 'fas fa-newspaper',
            'message' => "📚 New Article: '{$this->post->title}' is now available."
        ];
    }
}
