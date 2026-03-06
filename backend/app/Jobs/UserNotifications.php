<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Mail\UserNotificationeMessage;
use Mail;

class UserNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $text;
    protected $subject;
    protected $email;

    public $tries = 2; // limit to call this event   

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($const_url, $const_text, $const_subject, $const_email)
    {
        $this->url = $const_url;
        $this->text = $const_text;
        $this->subject = $const_subject;
        $this->email = $const_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emailData = array(
            'url' => $this->url,
            'subject' => $this->subject,
            'text' => $this->text,
        );

        Mail::to($this->email)->send(new UserNotificationeMessage($emailData));
    }
}
