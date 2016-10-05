<?php

namespace App\Jobs;

use App\Contracts\Authentication;
use App\Repositories\UserRepository;
use App\Events\UserHasBegunResetProcess;
use App\Exceptions\UserNotFoundException;
use Illuminate\Contracts\Bus\SelfHandling;

class BeginResetProcess extends Job implements SelfHandling
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

        if (!$objUser) {
            throw new UserNotFoundException();
        }

        $temporary = $auth->createTemporaryHash($email);
        $auth->createReminder($objUser);

        event(new UserHasBegunResetProcess($objUser, $temporary->hash));
    }
}
