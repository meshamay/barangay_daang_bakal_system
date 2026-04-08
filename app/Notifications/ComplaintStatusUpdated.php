<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Complaint;

class ComplaintStatusUpdated extends Notification
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
        $status = strtolower($this->complaint->status);
        $statusText = str_replace('_', ' ', $status);
        $complaintType = $this->complaint->complaint_type;
        $message = "Your {$complaintType} complaint {$transactionId} is now {$statusText}.";

        if ($status === 'completed') {
            $message .= ' If you have any additional concerns that require a face-to-face discussion, please visit the Barangay Hall for assistance.';
        }

        return [
            'type' => 'complaint',
            'category' => 'complaint_status_update',
            'title' => 'Complaint Status Update',
            'message' => $message,
            'link' => route('user.complaints.index'),
            'transaction_id' => $transactionId,
            'status' => $status,
        ];
    }

    private function getStatusMessage($status, $complaintType)
    {
        $status = strtolower(trim($status));
        
        $statusMessages = [
            'pending' => "Your {$complaintType} complaint has been received and is pending review. Our team will investigate and get back to you soon.",
            'in progress' => "Your complaint regarding {$complaintType} is currently under investigation. Our team is actively looking into your concern.",
            'investigating' => "Your {$complaintType} complaint is under investigation. We appreciate your patience as we gather all necessary information.",
            'approved' => "Your {$complaintType} complaint has been approved for investigation. We are proceeding with the case resolution.",
            'rejected' => "Unfortunately, your {$complaintType} complaint has been rejected. This may be due to insufficient information or it falling outside our jurisdiction. Please contact the Barangay Hall for clarification.",
            'resolved' => "Your complaint regarding {$complaintType} has been resolved.",
            'completed' => "Your complaint regarding {$complaintType} has been resolved.",
        ];
        
        $message = $statusMessages[$status] ?? "Your {$complaintType} complaint status has been updated to: {$status}.";
        return str_replace('{$complaintType}', $complaintType, $message);
    }

    public function toMail(object $notifiable)
    {
        $transactionId = $this->complaint->transaction_no;
        $status = strtolower(trim($this->complaint->status));
        $statusText = str_replace('_', ' ', $status);
        $complaintType = $this->complaint->complaint_type;

        $subject = "Complaint Status Update - {$transactionId}";
        $statusMessage = $this->getStatusMessage($this->complaint->status, $complaintType);

        if ($status === 'in progress') {
            $subject = 'Complaint Status Update - In Progress (Investigating)';
        } elseif ($status === 'completed' || $status === 'resolved') {
            $subject = 'Complaint Status Update - Completed (Resolved)';
        }

        $mailMessage = (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($subject)
            ->greeting('Hello!')
            ->line($statusMessage)
            ->line("Transaction ID: {$transactionId}");

        if ($status === 'in progress') {
            $mailMessage->line('We will keep you updated on the progress of your complaint.');
        }

        if ($status === 'completed' || $status === 'resolved') {
            $mailMessage->line('If you have any further concerns, you may contact Barangay Daang Bakal or visit the Barangay Hall during office hours (Monday to Friday, 7:00 AM – 5:00 PM) for a face-to-face discussion.');
        }

        return $mailMessage
            ->action('View Details', route('user.complaints.index'))
            ->salutation('Regards, Barangay Daang Bakal');
    }

    public function toSms(object $notifiable)
    {
        $transactionId = $this->complaint->transaction_no;
        $status = strtolower($this->complaint->status);
        $statusText = str_replace('_', ' ', $status);
        $complaintType = $this->complaint->complaint_type;

        $message = "Barangay Daang Bakal: Your {$complaintType} complaint {$transactionId} is now {$statusText}.";
        if ($status === 'completed') {
            $message .= ' Visit Barangay Hall if needed.';
        }

        return $message;
    }
}