<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class UserRegistrationRejected extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, ?string $reason = null)
    {
        $this->user = $user;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
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
        $message = (new MailMessage)
            ->subject('Your Registration Has Been Rejected')
            ->greeting('Hello ' . $this->user->first_name . '!')
            ->line('Unfortunately, your registration with Barangay Daang Bakal has been rejected.');

        if ($this->reason) {
            $message->line('Reason: ' . $this->reason);
        }

        $message->line('Please contact the barangay office for more information or to resubmit your application.')
            ->action('Contact Us', url('/contact'))
            ->line('If you have any questions, please reach out to the barangay office.')
            ->salutation('Best regards, Barangay Daang Bakal');

        return $message;
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $message = "Your registration has been rejected.";
        if ($this->reason) {
            $message .= " Reason: " . $this->reason;
        }

        return [
            'type' => 'registration',
            'category' => 'user_rejected',
            'title' => 'Registration Rejected',
            'message' => $message,
            'link' => route('user.dashboard'),
            'user_id' => $this->user->id,
            'reason' => $this->reason,
        ];
    }
}
