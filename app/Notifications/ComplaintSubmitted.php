<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Complaint;

class ComplaintSubmitted extends Notification
{
    use Queueable;

    public $complaint;

    /**
     * Create a new notification instance.
     */
    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail', 'sms'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $transactionId = $this->complaint->transaction_no;
        $complaintType = $this->complaint->complaint_type;

        return [
            'type' => 'complaint',
            'category' => 'complaint_submitted',
            'title' => 'Complaint Filed',
            'message' => "Your complaint {$transactionId} about {$complaintType} has been submitted successfully.",
            'link' => route('user.complaints.index'), 
            'transaction_id' => $transactionId,
        ];
    }

    public function toMail(object $notifiable)
    {
        $transactionId = $this->complaint->transaction_no;
        $complaintType = $this->complaint->complaint_type;

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Complaint Submitted Successfully')
            ->greeting('Hello!')
            ->line("Your complaint regarding {$complaintType} has been submitted successfully.")
            ->line("Transaction ID: {$transactionId}")
            ->line('You will receive updates on the status of your complaint.')
            ->action('View Details', route('user.complaints.index'))
            ->salutation('Regards, Barangay Daang Bakal');
    }

    public function toSms(object $notifiable)
    {
        $transactionId = $this->complaint->transaction_no;
        $complaintType = $this->complaint->complaint_type;

        return "Barangay Daang Bakal: Your {$complaintType} complaint {$transactionId} has been submitted. You will receive status updates.";
    }
}