<?php
// app/Mail/VerificationCodeMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $code) {}

    public function build(): self
    {
        return $this->subject('Код подтверждения регистрации')
                    ->view('emails.verification-code');
    }
}