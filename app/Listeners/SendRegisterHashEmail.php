<?php

namespace App\Listeners;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use App\Events\UserHasBegunRegisterProcess;

class SendRegisterHashEmail
{
    public function handle(UserHasBegunRegisterProcess $event)
    {
        $temporary = $event->temporary;

        Mail::queue('emails.register', compact('temporary'), function (Message $m) use ($temporary) {
            $m->to($temporary->email)->subject('Register your account.');
        });
    }
}
