<?php

namespace App\Jobs;

use App\Repositories\UserRepository;
use App\Contracts\Authentication;
use App\Exceptions\InvalidOrExpiredResetHash;
use App\Exceptions\UserNotFoundException;
use Illuminate\Contracts\Bus\SelfHandling;

class CompleteResetProcess extends Job implements SelfHandling
{
    private $password_confirmation;
    private $password;
    private $userId;
    private $code;

    private $user;
    private $auth;

    /**
     * Create a new job instance.
     */
    public function __construct($password, $password_confirmation, $userId, $code)
    {
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;
        $this->userId = $userId;
        $this->code = $code;
    }

    /**
     * Execute the job.
     */
    public function handle(UserRepository $user, Authentication $auth)
    {
        $this->user = $user;
        $this->auth = $auth;

        $objUser = $this->findUser();

        if (!$this->auth->completeResetPassword($objUser, $this->code, $this->password)) {
            throw new InvalidOrExpiredResetHash();
        }

        return $user;
    }

    private function findUser()
    {
        $objUser = $this->user->find($this->userId);
        if ($objUser) {
            return $objUser;
        }
        throw new UserNotFoundException();
    }
}
