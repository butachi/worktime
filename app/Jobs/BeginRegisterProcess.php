<?php

namespace App\Jobs;

use App\Contracts\Authentication;
use App\Repositories\UserRepository;
use App\Events\UserHasBegunRegisterProcess;
use App\Exceptions\UserAlreadyExistsException;
use Illuminate\Contracts\Bus\SelfHandling;

class BeginRegisterProcess extends Job implements SelfHandling
{
    private $email;

    /**
     * Create a new job instance.
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(Authentication $auth, UserRepository $user)
    {
        $email = $this->email;

        $objUser = $user->findByCredentials(compact('email'));

        if ($objUser) {
            throw new UserAlreadyExistsException();
        }
        $temporary = $auth->createTemporaryHash($email);

        event(new UserHasBegunRegisterProcess($temporary));
    }
}
