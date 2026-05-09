<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Program;

class NewProgramAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $program;

    /**
     * Create a new notification instance.
     */
    public function __construct(Program $program)
    {
        $this->program = $program;
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
            ->subject("New Course Available: {$this->program->name}")
            ->greeting("Salaam Representative of Allah on Earth!")
            ->line("We are excited to announce a new program on YPMMH: **{$this->program->name}**.")
            ->line($this->program->description ?? 'Discover our newest educational series and mentorship opportunities.')
            ->action('View Course', route('parent.programs.catalog'))
            ->line('Let\'s continue building a productive generation!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'program_id' => $this->program->id,
            'name' => $this->program->name,
            'type' => 'new_program_available',
            'icon' => 'fas fa-graduation-cap',
            'message' => "🎓 New Course Available: '{$this->program->name}'. Check it out!"
        ];
    }
}
