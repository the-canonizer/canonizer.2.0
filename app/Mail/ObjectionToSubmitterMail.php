<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class ObjectionToSubmitterMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $link;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $link,$data)
    {
        $this->user = $user;
        $this->link = $link;
	$this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.objectiontosubmitter')->subject($this->data['subject']);
    }
}
