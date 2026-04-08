<?php

// app/Notifications/DocumentRequestSubmitted.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DocumentRequest;

class DocumentRequestSubmitted extends Notification
{
    use Queueable;

    public $documentRequest;

    public function __construct(DocumentRequest $documentRequest)
    {
        $this->documentRequest = $documentRequest;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        $transactionId = $this->documentRequest->tracking_number;
        
        // Normalize document type name
        $documentType = str_replace(
            ['Certificate of Indigency', 'Certificate of Residency'],
            ['Indigency Clearance', 'Resident Certificate'],
            $this->documentRequest->document_type
        );
        
        // Use correct article (a/an) based on document type
        $article = in_array($documentType, ['Indigency Clearance']) ? 'an' : 'a';

        return [
            'type' => 'document',
            'category' => 'document_submitted',
            'title' => 'Request Submitted',
            'message' => "Your document request {$transactionId} for {$article} {$documentType} has been submitted successfully.",
            'link' => route('user.document-requests.index'), 
            'transaction_id' => $transactionId,
        ];
    }

    public function toMail(object $notifiable)
    {
        $transactionId = $this->documentRequest->tracking_number;
        $documentType = str_replace(
            ['Certificate of Indigency', 'Certificate of Residency'],
            ['Indigency Clearance', 'Resident Certificate'],
            $this->documentRequest->document_type
        );

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Document Request Submitted Successfully')
            ->greeting('Hello!')
            ->line("Your document request for {$documentType} has been submitted successfully.")
            ->line("Transaction ID: {$transactionId}")
            ->line('You will receive updates on the status of your document request.')
            ->action('View Details', route('user.document-requests.index'))
            ->salutation('Regards, Barangay Daang Bakal');
    }
}