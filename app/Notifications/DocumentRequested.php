<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentRequested extends Notification
{
    use Queueable;

    protected $trackingNumber;
    protected $documentType;

    /**
     * Create a new notification instance.
     */
    public function __construct($trackingNumber, $documentType)
    {
        $this->trackingNumber = $trackingNumber;
        $this->documentType = $documentType;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database', 'mail', 'sms'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'type' => 'document',
            'category' => 'document_requested',
            'title' => 'Document Requested',
            'message' => "You requested a {$this->documentType}.",
            'tracking_number' => $this->trackingNumber,
            'link' => route('user.document-requests.index'), 
            'created_at' => now(),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Document Request Submitted')
            ->view('emails.document-requested', [
                'documentType' => $this->documentType,
                'trackingNumber' => $this->trackingNumber,
            ]);
    }

    public function toSms($notifiable)
    {
        return "Barangay Daang Bakal: Your {$this->documentType} request {$this->trackingNumber} has been submitted. You will receive status updates.";
    }
}