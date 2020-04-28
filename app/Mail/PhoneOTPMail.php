<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class PhoneOTPMail extends Mailable
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
    public function __construct(User $user,$data)
    {
        $this->user = $user;
       
		$this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.phoneotp')->subject(config('app.mail_env').$this->data['subject']);
    }
}
