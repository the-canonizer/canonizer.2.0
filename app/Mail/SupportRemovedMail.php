<?php

namespace App\Mail;

use App\User;
use App\Facades\Util;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


/**
 * This Mailable will send mails to following
 * To the direct delegates of a delegate supporter who is withdrawing its delegate support
 */
class SupportRemovedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $link;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$link,$data)
    {
        $this->data = $data;
        $this->user = $user;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = Util::getEmailSubjectForSandbox($this->data['namespace_id']);
        return $this->markdown('emails.supporter.support_removed')->subject($subject.' '.$this->data['subject']);
    }
}
