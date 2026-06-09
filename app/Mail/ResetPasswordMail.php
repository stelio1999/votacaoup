<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $resetLink;
    public $user;
    public $token;

    public function __construct($resetLink, User $user, $token)
    {
        $this->resetLink = $resetLink;
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Redefinição de Palavra-passe - Sistema de Votação UP Maputo')
                    ->view('auth.passwords.reset-password')
                    ->with([
                        'resetLink' => $this->resetLink,
                        'userName' => $this->user->name,
                        'userEmail' => $this->user->email,
                        'token' => $this->token,
                        'expiryMinutes' => 60,
                    ]);
    }
}