<?php

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class VerifyEmail extends VerifyEmailBase
{
    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(Lang::get('Welcome to YPMMH! – Please Verify Your Registration'))
            ->greeting('As-salamu alaykum ' . $this->user?->first_name . ',')
            ->line(Lang::get('Alhamdulillah! Your account creation was successful. We are excited to have you join our community.'))
            ->line(Lang::get('To ensure the security of your account and complete your registration, please verify your email address by clicking the button below.'))
            ->action(Lang::get('Verify Email Address'), $url)
            ->line(Lang::get('Please verify your account within 3 days to avoid account suspension.'))
            ->line(Lang::get('If you did not create an account, no further action is required.'))
            ->line(Lang::get('Note: If you do not see this email in your inbox, please check your spam or junk folder.'))
            ->salutation('Best regards, The YPMMH Team');
    }

    // We need to pass the user instance or ensure it's available via $notifiable
    // The base class uses $notifiable in toMail, so we can likely access it.
    // Actually buildMailMessage is usually passed the user if we overrode the main VerifyEmail
    // But VerifyEmailBase calculates the URL in toMail. 

    // Let's override toMail completely to be safe and consistent
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        $this->user = $notifiable;

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return $this->buildMailMessage($verificationUrl);
    }

    protected $user;
}
