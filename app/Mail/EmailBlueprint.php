<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailBlueprint extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;
    public $options;

    /**
     * Create a new message instance.
     *
     * @param  string  $subject
     * @param  string  $body
     * @param  array  $options
     * @return void
     */
    public function __construct($subject, $body, $options)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->options = $options;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('emails.email-blueprint')
                    ->with([
                        'body' => $this->body,
                    ]);
    }
}
