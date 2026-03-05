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
        return ['database'];
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
}