<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from($this->data['from']['address'], $this->data['from']['name'])
            ->to($this->data['to']['address'], $this->data['to']['name'])
            ->subject($this->data['subject'])
            ->view('emails.createaccount')
            ->with(['reg_name' => $this->data['reg_name'], 'reg_password' => $this->data['reg_password']]);
    }
}
