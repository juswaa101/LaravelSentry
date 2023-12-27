<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class SendPasswordResetNotification extends Mailable
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
            'password.reset.form',
            now()->addMinutes(30),
            ['token' => $this->user->token]
        );

        return $this->markdown('mail.auth.SendPasswordResetNotification')
            ->subject('Password Reset')
            ->with([
                'user' => $this->user,
                'token' => $this->user->token,
                'resetUrl' => $url,
            ]);
    }
}
