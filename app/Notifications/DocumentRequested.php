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
        return ['database']; // This tells Laravel to save it in your 'notifications' table
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
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
}