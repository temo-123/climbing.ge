<?php

namespace App\Notification;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FollowingNotification extends Notification
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($EmailArray)
    {
        $this->email = $EmailArray['email'];
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
        $service = $this->check_site($this->from_site);

        $subject = 'User message from the site '.$service;
        
        return $this->markdown('/emails/FollowingNotificationMessage')->with([
            'email'=>$this->email,
            'msg'=>$this->msg,
            'from_site'=>$service,
        ])->subject($subject);
    }

    public function check_site($site_id)
    {
        if ($site_id == 1) {
            return 'climbing.ge';
        }
        else if ($site_id == 2) {
            return 'shop.climbing.ge';
        } 
        else {
            return 'films.climbing.ge';
        }
        
    }
}
