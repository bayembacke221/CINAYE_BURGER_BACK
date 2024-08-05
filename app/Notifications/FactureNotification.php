<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Commande;

class FactureNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $commande;
    protected $pdfContent;

    public function __construct(Commande $commande, $pdfContent)
    {
        $this->commande = $commande;
        $this->pdfContent = $pdfContent;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Votre facture pour la commande #' . $this->commande->id)
            ->line('Merci pour votre commande chez CINAYE BURGER.')
            ->line('Veuillez trouver ci-joint votre facture.')
            ->attachData($this->pdfContent, 'facture.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
