<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowingNotification extends Mailable
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
        $this->unfollow_url = $EmailArray['unfollow_url'];
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

        $subject = 'Following '.$service;
        
        return $this->markdown('emails.FollowingNotificationMessage')->with([
            'email'=>$this->email,
            'msg'=>$this->msg,
            'unfollow_url'=>$this->unfollow_url,
            'from_site'=>$service,
        ])->subject($subject);
    }

    public function check_site($site_id)
    {
        if ($site_id == 'guid') {
            return 'climbing.ge';
        }
        else if ($site_id == 'shop') {
            return 'shop.climbing.ge';
        } 
        // else if ($site_id == 'cinema') {
        //     return 'films.climbing.ge';
        // }
        
    }
}
