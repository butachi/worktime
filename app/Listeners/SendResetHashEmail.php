<?php

namespace App\Listeners;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use App\Events\UserHasBegunResetProcess;

class SendResetHashEmail
{
    public function handle(UserHasBegunResetProcess $event)
    {
        $user = $event->user;

        $code = $event->hash;

        Mail::queue('emails.reminder', compact('user', 'code'), function (Message $m) use ($user) {
            $m->to($user->email)->subject('Reset your account password.');
        });
    }
}
