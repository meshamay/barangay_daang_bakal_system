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
        $complaintType = $this->complaint->complaint_type;

        return [
            'type' => 'complaint',
            'category' => 'complaint_status_update',
            'title' => 'Complaint Status Update',
            'message' => "Your complaint {$transactionId} regarding {$complaintType} is now {$status}.",
            'link' => route('user.complaints.index'),
            'transaction_id' => $transactionId,
            'status' => $status,
        ];
    }
}