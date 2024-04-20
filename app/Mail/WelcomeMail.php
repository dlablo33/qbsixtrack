<?php
// WelcomeMail.php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $merchant;
    public $password;

    public function __construct($merchant, $password)
    {
        $this->merchant = $merchant;
        $this->password = $password;
    }

    public function build()
    {
        return $this->view('emails.welcome')
            ->subject('Welcome to Quickbook Panelone')
            ->with(['merchant' => $this->merchant, 'password' => $this->password]);
    }
}
