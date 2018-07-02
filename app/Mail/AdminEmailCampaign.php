<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class AdminEmailCampaign extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $template)
    {
        $this->user = $user;
	$this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.mailcampaign')->subject($this->template->subject);
    }
}
