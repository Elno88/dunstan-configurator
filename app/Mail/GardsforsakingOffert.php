<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GardsforsakingOffert extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    public $subject;
    public $file;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $details, $file = null)
    {
        $this->subject = $subject;
        $this->details = $details;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject($this->subject)
            ->view('emails.gardsforsakring_offert')
            ->attachData($this->file, 'Insurely.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
