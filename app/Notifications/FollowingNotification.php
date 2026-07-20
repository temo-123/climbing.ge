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

    protected $email;
    protected $msg;
    protected $unfollow_url;
    protected $from_site;

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

        $subject = "You're now following ".$service;
        
        return $this->markdown('emails.FollowingNotificationMessage')->with([
            'email'=>$this->email,
            'msg'=>$this->msg,
            'unfollow_url'=>$this->unfollow_url,
            'from_site'=>$service,
        ])->subject($subject);
    }

    public function check_site($site_id)
    {
        return match ($site_id) {
            'guid' => config('app.site_url'),
            'shop' => config('app.shop_url'),
            'blog' => config('app.blog_url'),
            'summit' => config('app.summit_url'),
            'films', 'cinema' => config('app.films_url'),
            default => config('app.site_url'),
        };
    }
}
