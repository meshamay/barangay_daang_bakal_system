<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DocumentRequest;

class DocumentRequestSubmittedAdmin extends Notification
{
    use Queueable;

    public $documentRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(DocumentRequest $documentRequest)
    {
        $this->documentRequest = $documentRequest;
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
        $transactionId = $this->documentRequest->tracking_number;
        $user = $this->documentRequest->resident ?? \App\Models\User::find($this->documentRequest->resident_id);
        $documentType = $this->documentRequest->document_type;
        
        // Use date_requested if available, otherwise created_at, otherwise now
        $date = $this->documentRequest->date_requested ?? $this->documentRequest->created_at ?? now();
        $dateTime = $date instanceof \Carbon\Carbon ? $date->format('d/m/Y') : \Carbon\Carbon::parse($date)->format('d/m/Y');

        $firstName = $user->first_name ?? 'Unknown';
        $lastName = $user->last_name ?? 'Resident';
        $fullName = trim("{$firstName} {$lastName}");

        return [
            'type' => 'document',
            'category' => 'document_request_submitted',
            'title' => 'New Document Request',
            'message' => "({$fullName}) ({$transactionId}) submitted a Document Request for {$documentType}.",
            'date_filed' => $dateTime,
            'link' => url('/admin/documents'), 
            'transaction_id' => $transactionId,
        ];
    }
}
