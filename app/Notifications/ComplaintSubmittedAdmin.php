<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintSubmittedAdmin extends Notification
{
    use Queueable;

    public $complaint;

    /**
     * Create a new notification instance.
     */
    public function __construct($complaint)
    {
        $this->complaint = $complaint;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $transactionId = $this->complaint->transaction_no;
        $user = $this->complaint->user;
        $complaintType = $this->complaint->complaint_type;
        $dateTime = $this->complaint->created_at->format('d/m/Y');
        $fullName = trim("{$user->first_name} {$user->last_name}");

        return [
            'type' => 'complaint',
            'category' => 'complaint_submitted',
            'title' => 'New Complaint Filed',
            'message' => "({$fullName}) ({$transactionId}) submitted a Complaint about {$complaintType}.",
            'date_filed' => $dateTime,
            'link' => url('/admin/complaints'),
            'transaction_id' => $transactionId,
        ];
    }
}
