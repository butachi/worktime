<?php

namespace App\Listeners;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use App\Events\UserHasCompleteRegisterProcess;

class SendRegisterCompleteEmail
{
    public function handle(UserHasCompleteRegisterProcess $event)
    {
        $temporary = $event->temporary;

        Mail::queue('emails.welcome', compact('temporary'), function (Message $m) use ($temporary) {
            $m->to($temporary->email)->subject('Welcome Hawaiioption.');
        });
    }
}
