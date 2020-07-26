<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class OtpVerificationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $from_login = false;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$fromLogin=false)
    {
        $this->user = $user;
        $this->from_login = $fromLogin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->from_login){
            return $this->markdown('emails.login_otp')->subject(config('app.mail_env').'One Time Verification Code');
        }else{
            return $this->markdown('emails.registeration_otp')->subject(config('app.mail_env').'One Time Verification Code');    
        }
        
    }
}
