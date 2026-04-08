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
        return ['database', 'mail', 'sms'];
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

    private function getStatusMessage($status, $documentType)
    {
        $status = strtolower(trim($status));
        
        $statusMessages = [
            'pending' => "Your {$documentType} request is being reviewed. Please wait for the next update.",
            'in progress' => "Your {$documentType} request is currently being processed. Our team is actively working on fulfilling your request.",
            'processing' => "Your {$documentType} request is currently being processed. Our team is actively working on fulfilling your request.",
            'reviewed' => "Your {$documentType} request has been reviewed. It will be prepared shortly.",
            'approved' => "Great! Your {$documentType} request has been approved. Your document is now being prepared.",
            'rejected' => "Unfortunately, your {$documentType} request has been rejected. Please contact the Barangay Hall for more information.",
            'completed' => "Your {$documentType} request has been completed.",
            'ready for pickup' => "Your {$documentType} request has been completed.",
        ];
        
        $message = $statusMessages[$status] ?? "Your {$documentType} request status has been updated to: {$status}.";
        return str_replace('{$documentType}', $documentType, $message);
    }

    public function toMail(object $notifiable)
    {
        $transactionId = $this->documentRequest->tracking_number;
        $status = strtolower(trim($this->documentRequest->status));
        
        // Normalize document type name
        $documentType = str_replace(
            ['Certificate of Indigency', 'Certificate of Residency'],
            ['Indigency Clearance', 'Resident Certificate'],
            $this->documentRequest->document_type
        );

        $subject = "Document Request Status Update - {$transactionId}";
        if ($status === 'in progress' || $status === 'processing') {
            $subject = 'Document Request Status Update – In Progress (Processing)';
        } elseif ($status === 'completed' || $status === 'ready for pickup') {
            $subject = 'Document Request Status Update – Completed (Ready for Claim)';
        }

        $statusMessage = $this->getStatusMessage($status, $documentType);

        $mailMessage = (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($subject)
            ->greeting('Hello!')
            ->line($statusMessage)
            ->line("Transaction ID: {$transactionId}");

        if ($status === 'in progress' || $status === 'processing') {
            $mailMessage->line('We will keep you updated on the progress of your request.');
        }

        if ($status === 'completed' || $status === 'ready for pickup') {
            $mailMessage->line('You may now claim your document at the Barangay Hall during office hours (Monday to Friday, 7:00 AM – 5:00 PM). Please bring a valid ID or present your Transaction ID when claiming your document.')
                ->line('If you have any further questions, you may also contact Barangay Daang Bakal.');
        }

        return $mailMessage
            ->action('View Details', route('user.document-requests.index'))
            ->salutation('Regards, Barangay Daang Bakal');
    }

    public function toSms(object $notifiable)
    {
        $transactionId = $this->documentRequest->tracking_number;
        $status = $this->documentRequest->status;
        
        // Normalize document type name
        $documentType = str_replace(
            ['Certificate of Indigency', 'Certificate of Residency'],
            ['Indigency Clearance', 'Resident Certificate'],
            $this->documentRequest->document_type
        );

        $message = "Barangay Daang Bakal: Your {$documentType} request {$transactionId} is now {$status}.";
        if (strtolower($status) === 'completed') {
            $message .= ' Claim at Barangay Hall.';
        }

        return $message;
    }
}