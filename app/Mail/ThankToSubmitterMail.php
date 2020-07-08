<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class ThankToSubmitterMail extends Mailable
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
    public function __construct($user, $link,$data)
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
          return $this->markdown('emails.thanktosubmitter')->subject(config('app.mail_env').'Thank you for contributing to Canonizer.com');
    }
}
