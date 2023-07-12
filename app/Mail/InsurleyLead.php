<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InsurleyLead extends Mailable
{
    use Queueable, SerializesModels;

    public $file;
    public $date;
    public $custom;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($file, $date = null)
    {
        $this->file = $file;
        $this->date = !empty($date) ? $date : now()->toDateString();
        $this->custom = !empty($date) ? true : false;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Leads via Insurley - ' . $this->date)
            ->markdown('emails.insurley_lead')
            ->attachFromStorage($this->file);
    }
}
