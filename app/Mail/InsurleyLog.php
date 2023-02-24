<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InsurleyLog extends Mailable
{
    use Queueable, SerializesModels;

    public $logfile_path;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $logfile_path)
    {
        $this->subject = $subject;
        $this->logfile_path = $logfile_path;
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
            ->view('emails.insurley_log')
            ->attachFromStorage($this->logfile_path);
    }
}
