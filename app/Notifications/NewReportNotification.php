<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\Report $report)
    {
        $this->report = $report;
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
            ->subject('New Content Reported')
            ->line('A user has reported content on the platform.')
            ->line('Reporter: ' . $this->report->reporter->first_name . ' ' . $this->report->reporter->last_name)
            ->line('Reason: ' . $this->report->reason)
            ->line('Type: ' . class_basename($this->report->reportable_type))
            ->action('View Reports', url('/admin/reports'))
            ->line('Please investigate this issue.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'reporter_id' => $this->report->reporter_id,
            'reason' => $this->report->reason,
            'type' => class_basename($this->report->reportable_type),
        ];
    }
}
