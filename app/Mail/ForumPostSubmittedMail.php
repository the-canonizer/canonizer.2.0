<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class ForumPostSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $link;
    public $data;

    public function __construct (User $user, $link, $data)
    {
        $this->user = $user;
        $this->link = $link;
        $this->data = $data;
    }

    /**
     * [build description]
     * @return [type] [description]
     */
    public function build()
    {
        return $this->markdown('emails.forumpostsubmitemail')->
                      subject(config('app.mail_env').$this->data['subject']);
    }
}

?>
