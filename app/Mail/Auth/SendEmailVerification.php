<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class SendEmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = URL::temporarySignedRoute(
            'verify.form',
            now()->addMinutes(30),
            ['token' => $this->user->token]
        );

        return $this->markdown('mail.auth.SendEmailVerification')
            ->subject('Email Verification')
            ->with([
                'user' => $this->user,
                'url' => $url,
            ]);
    }
}
