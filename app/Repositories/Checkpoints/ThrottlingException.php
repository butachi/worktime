<?php

namespace App\Repositories\Checkpoints;

use Carbon\Carbon;
use RuntimeException;

class ThrottlingException extends RuntimeException
{
    /**
     * The delay, in seconds.
     *
     * @var string
     */
    protected $delay;

    /**
     * The throttling type which caused the exception.
     *
     * @var string
     */
    protected $type;

    /**
     * Returns the delay.
     *
     * @return int
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Sets the delay.
     *
     * @param int $delay
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
    }

    /**
     * Returns the type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns a Carbon object representing the time which the throttle is lifted.
     *
     * @return \Carbon\Carbon
     */
    public function getFree()
    {
        return Carbon::now()->addSeconds($this->delay);
    }
}
