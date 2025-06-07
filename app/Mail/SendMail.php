<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $content;
    public $subject;
    public $view;

    public function __construct($content, $subject, $view)
    {
        $this->content = $content;
        $this->subject = $subject;
        $this->view = $view;
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view($this->view)
            ->with('data', $this->content);
    }
}
