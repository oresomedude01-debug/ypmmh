<?php

namespace App\Notifications;

use App\Models\Program;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Sent to parents when a program is a great fit for one or more of their children.
 * Delivered as a database + optional mail notification.
 * Push is handled separately via the PWA service-worker payload.
 */
class ProgramSpotlightNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Program $program;
    protected User    $child;        // The specific child this recommendation targets
    protected string  $reason;       // Short human-readable reason for the match

    public function __construct(Program $program, User $child, string $reason = '')
    {
        $this->program = $program;
        $this->child   = $child;
        $this->reason  = $reason ?: "Perfect for {$child->first_name}'s age group";
    }

    public function via(object $notifiable): array
    {
        return ['database'];        // In-app; push triggered separately via JS
    }

    /**
     * Database / in-app notification payload.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'program_spotlight',
            'program_id'  => $this->program->id,
            'program_name' => $this->program->name,
            'child_id'    => $this->child->id,
            'child_name'  => $this->child->first_name,
            'reason'      => $this->reason,
            'icon'        => 'fas fa-star',
            'thumbnail'   => $this->program->thumbnail_path,
            'url'         => route('parent.programs.catalog'),
            'message'     => "🌟 {$this->program->name} looks perfect for {$this->child->first_name}! {$this->reason}",
        ];
    }
}
