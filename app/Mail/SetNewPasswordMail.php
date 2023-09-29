<?php
/*
 * @Author: Lucas Brito
 * @Data: 2021-01-31 16:39:59
 * @Último Editor: Lucas Brito
 * @Última Hora da Edição: 2021-01-31 17:05:57
 * @Caminho do Arquivo: \TheLegends\app\Mail\SetNewPasswordMail.php
 * @Descrição:
 */

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SetNewPasswordMail extends Mailable
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
            ->view('emails.setnewpassword', [
                'name' => $this->data['name'],
                'newpassword' => $this->data['newpassword']
            ]);
    }
}
