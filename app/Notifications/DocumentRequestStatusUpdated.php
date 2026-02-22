<?php

// app/Notifications/DocumentRequestStatusUpdated.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\DocumentRequest;

class DocumentRequestStatusUpdated extends Notification
{
    use Queueable;

    public $documentRequest;

    public function __construct(DocumentRequest $documentRequest)
    {
        $this->documentRequest = $documentRequest;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $transactionId = $this->documentRequest->tracking_number;
        $status = $this->documentRequest->status;
        
        // Normalize document type name
        $documentType = str_replace(
            ['Certificate of Indigency', 'Certificate of Residency'],
            ['Indigency Clearance', 'Resident Certificate'],
            $this->documentRequest->document_type
        );

        $message = "Your {$documentType} request {$transactionId} is now {$status}.";
        if (strtolower($status) === 'completed') {
            $message .= ' You may claim your document at the Barangay Hall during office hours.';
        }

        return [
            'type' => 'document',
            'category' => 'document_status_update',
            'title' => 'Request Status Update',
            'message' => $message,
            'link' => route('user.document-requests.index'),
            'transaction_id' => $transactionId,
            'status' => $status,
        ];
    }
}