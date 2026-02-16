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

        return [
            'type' => 'document',
            'category' => 'document_status_update',
            'title' => 'Request Status Update',
            'message' => "Your request {$transactionId} is now {$status}.",
            'link' => route('user.document-requests.index'),
            'transaction_id' => $transactionId,
            'status' => $status,
        ];
    }
}