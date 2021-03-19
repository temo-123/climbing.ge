<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Message extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($EmailArray)
    {
        $this->name = $EmailArray['name'];
        $this->surname = $EmailArray['surname'];
        $this->email = $EmailArray['email'];
        $this->num = $EmailArray['num'];
        $this->country = $EmailArray['country'];
        $this->msg = $EmailArray['message'];
        $this->from_site = $EmailArray['from_site'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'User message from the site '.$this->from_site;
        
        return $this->markdown('site/emails/message')->with([
            'name'=>$this->name,
            'surname'=>$this->surname,
            'email'=>$this->email,
            'num'=>$this->num,
            'country'=>$this->country,
            'msg'=>$this->msg,
            'from_site'=>$this->from_site,
        ])->subject($subject);
    }
}
