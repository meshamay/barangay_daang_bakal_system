<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class UserRegistrationApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        return (new MailMessage)
            ->subject('Your Registration Has Been Approved')
            ->greeting('Hello ' . $this->user->first_name . '!')
            ->line('Good news! Your registration with Barangay Daang Bakal has been approved.')
            ->line('Resident ID: ' . $this->user->resident_id)
            ->line('You can now access all services and features available to residents.')
            ->action('Login Now', url('/login'))
            ->line('If you have any questions, please contact the barangay office.')
            ->salutation('Best regards, Barangay Daang Bakal');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'registration',
            'category' => 'user_approved',
            'title' => 'Registration Approved',
            'message' => "Welcome! Your registration has been approved. Your Resident ID is {$this->user->resident_id}. You can now log in and access all services.",
            'link' => route('user.dashboard'),
            'user_id' => $this->user->id,
            'resident_id' => $this->user->resident_id,
        ];
    }
}
